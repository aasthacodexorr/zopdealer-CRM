<?php

namespace Tenant\app\Libraries\Fbads;

use Exception;
use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use Tenant\app\Helpers\SocialUtility;
use Tenant\app\Repositories\ConfigurationRepository as Configuration;

/**
 * FacebookAds : Ads Insights class functions
 *
 * @package FbAds
 */
class FbadsInsights
{
    private $data = [];
    private $app_id;
    private $app_secret;
    private $access_token;
    private $ad_account_id;

    private $ad_account_original_id;
    private $fields = [
        'campaign_id',
        'campaign_name',
        'reach',
        'spend',
        'impressions',
        'cpm',
        'clicks',
        'cpc',
        'outbound_clicks',
        'cost_per_outbound_click',
        'cost_per_unique_outbound_click',
        'cost_per_action_type',
        'actions',
    ];

    public function __construct()
    {
        $this->app_id = SocialUtility::getValueFromEnv('fbads.appid');
        $this->app_secret = SocialUtility::getValueFromEnv('fbads.secret');
        $this->access_token = SocialUtility::getValueFromEnv('fbads.token');
        $configuration = (new Configuration())->findConfigurationColumnsByKey('fbads_account_id');
        $this->ad_account_original_id = $configuration->value;
        $this->ad_account_id = 'act_' . $this->ad_account_original_id;
        $graphVersion = SocialUtility::getValueFromEnv('fbads.version');

        $app = Api::init($this->app_id, $this->app_secret, $this->access_token);
        $app->setDefaultGraphVersion($graphVersion);
    }

    /**
     * Sends a GET request to Insights AdsAccount API and returns the result.
     *
     * @return $campaign
     * @throws Exception
     */
    public function getFbCampaigns(): array
    {
        $campaign = [];
        $fbads = [];

        try {                        
            if(!$this->ad_account_original_id):
                 throw new Exception('Facebook Ads Account ID is missing');
            endif;

            $api = Api::instance();
            //  $api->setLogger(new CurlLogger());
            $fields = [
                'id',
                'name',
            ];

            $params = [
                'effective_status' => array('ACTIVE'),
            ];

            $fbads = (new AdAccount($this->ad_account_id))->getCampaigns($fields, $params)->getResponse()->getContent();
            if (count($fbads['data']) > 0) {
                foreach ($fbads['data'] as $key => $val) {
                    $campaign[$val['id']] = $val['name'];
                }
            }
        } catch (\Exception $e) {
            $campaign['error'] = 'ERROR: ' . $e->getMessage();
        }
        return $campaign;
    }

    /**
     * Sends a GET request to Insights AdsAccount API and returns the result.
     *
     * @param string|null $timerange_from
     * @param string|null $timerange_to
     *
     * @return $campaignstats
     * @throws Exception
     */
    public function getFbAllCampaignStats($campaignId = '', $timerange_from = "", $timerange_to = ''): array
    {
        $campaignstats = [];
        $params = array(
            'filtering' => [],
            'level' => 'campaign',
            'breakdowns' => [],
            // 'date_preset' => 'last_3d',
            'default_summary' => true
        );

        if ($timerange_from != "" && $timerange_to !== "" ) {
            $params['time_range'] = ['since' => $timerange_from, 'until' => $timerange_to];
        }

        try {
            $api = Api::instance();
            $accountId = ($campaignId == "") ? $this->ad_account_id : $campaignId;
            $campaignstats = (new AdAccount($accountId))->getInsights($this->fields, $params)->getResponse()->getContent();
        } catch (\Exception $e) {
            $campaignstats['error'] = 'ERROR: ' . $e->getMessage();
        }

        return $campaignstats;
    }   
}