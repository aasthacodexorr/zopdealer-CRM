<?php

namespace Tenant\app\Controllers;

use Exception;
use Tenant\app\Libraries\Fbads\FbadsInsights;
use Tenant\app\Repositories\ConfigurationRepository as Configuration;
use Tenant\app\Helpers\SocialUtility;

class AdsController extends \App\Controllers\BaseController
{
    private $data = [];

    /**
     * Fetch all campaign name|id for a given account from DB
     *
     */
    public function getCampaigns()
    {
        $fbads_campaigns = json_decode(((new Configuration())->findConfigurationColumnsByKey('fbads_insights_account', ['value']))->value, true);
        $fbads_campaigns_valid = $this->getAllValidCampaign();
        SocialUtility::keepKeysInAssociativeArray($fbads_campaigns, $fbads_campaigns_valid);

        $this->data = $fbads_campaigns;
        $this->response->setContentType('application/json')->setJSON($this->data)->send();
    }

    /**
     * Fetch all stats for all campaigns or single campaigns
     *
     */
    public function getAllCampaignStats()
    {
        $fbCampaignStats = [];
        $request = \Config\Services::request();
        $campaignId = $request->getGet('cid') ?? "";
        $timerange_to = $request->getGet('tuntil') ?? "";
        $timerange_from = $request->getGet('tsince') ?? "";
        $percentage = (new Configuration())->findConfigurationColumnsByKey('fbads_value', ['value'])->value;
        $budget = (new Configuration())->findConfigurationColumnsByKey('fbads_budget', ['value'])->value;

        // Skip the Main Collective data
        if ($campaignId != "") {

            try {
                $fbCampaignStats = (new FbadsInsights)->getFbAllCampaignStats($campaignId, $timerange_from, $timerange_to);
                
                if (count($fbCampaignStats) > 1) {
                    $fbCampaignStatsValid = ["summary"];
                    $campaign_id = $fbCampaignStats['data'][0]['campaign_id'] ?? "";
                    $campaign_name = $fbCampaignStats['data'][0]['campaign_name'] ?? "";
                    SocialUtility::keepKeysInAssociativeArray($fbCampaignStats, $fbCampaignStatsValid);

                    $fbCampaignStats['data'][0]['campaign_id'] = $campaign_id;
                    $fbCampaignStats['data'][0]['campaign_name'] = $campaign_name;                   

                    $this->increaseValuesByPercentage($fbCampaignStats['summary']['reach'], $percentage, 'round');
                    $this->increaseValuesByPercentage($fbCampaignStats['summary']['spend'], $percentage, 'float');
                    $this->increaseValuesByPercentage($fbCampaignStats['summary']['impressions'], $percentage, 'round');
                    $this->increaseValuesByPercentage($fbCampaignStats['summary']['cpm'], $percentage, 'float');
                    $this->increaseValuesByPercentage($fbCampaignStats['summary']['clicks'], $percentage, 'round');
                    $this->increaseValuesByPercentage($fbCampaignStats['summary']['cpc'], $percentage, 'float');

                    foreach ($fbCampaignStats['summary']['outbound_clicks'] as $c => $d) {
                        if ($d['action_type'] == "outbound_click") {
                            $this->increaseValuesByPercentage($fbCampaignStats['summary']['outbound_clicks'][$c]['value'], $percentage, 'round');
                        }
                    }

                    foreach ($fbCampaignStats['summary']['cost_per_outbound_click'] as $c => $d) {
                        if ($d['action_type'] == "outbound_click") {
                            $this->increaseValuesByPercentage($fbCampaignStats['summary']['cost_per_outbound_click'][$c]['value'], $percentage, 'float');
                        }
                    }

                    foreach ($fbCampaignStats['summary']['actions'] as $c => $d) {
                        if ($d['action_type'] ==  "landing_page_view") {
                            $this->increaseValuesByPercentage($fbCampaignStats['summary']['actions'][$c]['value'], $percentage, 'round');
                        }
                    }

                    foreach ($fbCampaignStats['summary']['cost_per_action_type'] as $c => $d) {
                        if ($d['action_type'] == ("lead" || "landing_page_view")) {
                            $this->increaseValuesByPercentage($fbCampaignStats['summary']['cost_per_action_type'][$c]['value'], $percentage, 'float');
                        }
                    }
                }

            } catch (Exception $e) {
                $response['error'] = 'Code returned an error: ' . $e->getMessage();
            }
        }
        
        $fbCampaignStats['budget'] = $budget;

        $this->data = $fbCampaignStats;
        $this->response->setContentType('application/json')->setJSON($this->data)->send();
    }


    /**
     * Add value to X percentage
     *  
     * @param array $array
     * @param array $keyToRemove    
     * @return void
     */
    public function increaseValuesByPercentage(&$attribute, $percentage, $type): void
    {
        $factor = 1 + ($percentage / 100);
        if ($type == 'round') {
            $attribute = round($attribute * $factor);
        }
        if ($type == 'float') {
            $attribute = round($attribute * $factor, 2);
        }
    }


    /**
     * Fetch all campaign name|id for a given account from facebook
     *
     */
    public function getCampaignsAndSave()
    {
        $responseData = (new FbadsInsights)->getFbCampaigns();

        if (isset($responseData['error'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($responseData)->send();
            exit;
        }

        $configuration = new Configuration();
        $records = $configuration->setConfiguration(['fbads_insights_account' => json_encode($responseData)]);

        if (isset($records['success'])) {
            $this->response->setContentType('application/json')->setJSON($records)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($records)->send();
        }
    }

    /**
     * Fetch all valid campaigns
     *
     */
    private function getAllValidCampaign(): array
    {
        $validCampaigns = [];
        $campaigns = (new Configuration())->findConfigurationColumnsByKey('default_fbads_insights', ['value']);
        $validCampaigns = $campaigns->value;

        return json_decode($validCampaigns, true);
    }

}