<?php

namespace Tenant\app\Libraries\Valuation;

use Override;
use Tenant\app\Libraries\VinHistory\CarfaxV3;
use Tenant\app\Repositories\MarketValuationRepository;

/**
 * Carfax
 */
class Carfax extends CarfaxV3
{
	private MarketValuationRepository $market_valuation_repository;

	#[Override]
	public function __construct()
	{
		$this->market_valuation_repository = new MarketValuationRepository();
		return parent::__construct();
	}

	public static function create()
	{
		$ci_env = getenv('CI_ENVIRONMENT');

		if ($ci_env === 'development') {
			return new DummyCarfaxValuation();
		}

		return new Carfax();
	}

	public function lookupVehicle(array $data = []): array
	{
		try {

			$this->getToken();

			if (!empty($this->cache_factory->get('ZD_CARFAX_ACCESS_TOKEN'))) {
				return $this->requestDecodeAPI($data, $this->getToken());
			}
		} catch (\Exception $ex) {
			log_message('info', "Some error was there in processing this request : " . $ex->getMessage());
			return ['errors' => $ex->getMessage()];
		}

		return [
			'errors' => 'Required access token is missing'
		];
	}

	public function valuation(array $data = []): array
	{
		try {

			$this->getToken();

			if (!empty($this->cache_factory->get('ZD_CARFAX_ACCESS_TOKEN'))) {
				$response = $this->requestValuationAPI($data, $this->getToken());
				if (!empty($response['responseData'])) {
					if (!empty($response['responseData']['vehicleInformation'])) {
						$attributes = ['year', 'make', 'model', 'trim', 'vin'];
						foreach ($attributes as $attribute) {
							if (!empty($response['responseData']['vehicleInformation'][$attribute])) {
								$data[$attribute] = $response['responseData']['vehicleInformation'][$attribute];
							}
						}
					}
				}

				$data['status'] = !empty($response['errors']) || !empty($response['error']) ? ValuationConstant::FAILED : ValuationConstant::SUCCESS;
				$data['payload'] = json_encode($response);
				$this->market_valuation_repository->create($data);
				return $response;
			}
		} catch (\Exception $ex) {
			log_message('info', "Some error was there in processing this request : " . $ex->getMessage());
			return ['errors' => $ex->getMessage()];
		}

		return [
			'errors' => 'Required access token is missing'
		];
	}

	protected function requestDecodeAPI(array $data, string $access_token): ?array
	{

		if (empty($data) || empty($access_token)) {
			return [
				'errors' => 'Required data or access token is missing.'
			];
		}



		$url = "https://api.carfax.ca/vindecode/api/v2/SingleVin/SingleVinDecode?";
		$url .= http_build_query($data);

		$headers = [
			'Accept: application/json',
			"Authorization: Bearer $access_token"
		];

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			return [
				'errors' =>  curl_error($ch)
			];
		}
		curl_close($ch);

		$result = json_decode($result, true);

		if (intval($result['ResultCode']) !== 1) {
			$result['errors'] = $result['ResultMessage'];
		}

		return $result;
	}

	protected function requestValuationAPI(array $data, string $access_token): ?array
	{
		if (empty($data) || empty($access_token)) {
			return [
				'errors' => 'Required data or access token is missing.'
			];
		}

		$url = "https://valuationapi.carfax.ca/api/v2/SingleVinValuation/SingleVinValuation";

		$headers = [
			'Content-Type: application/json',
			'Accept: application/json',
			"Authorization: Bearer $access_token"
		];

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			return [
				'errors' =>  curl_error($ch)
			];
		}
		curl_close($ch);

		$result = json_decode($result, true);

		if (intval($result['resultCode']) !== 1) {
			$result['errors'] = $result['resultMessage'];
		}

		return $result;
	}
}
