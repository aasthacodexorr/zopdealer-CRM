<?php

namespace Tenant\app\Libraries\Valuation;

class DummyCarfaxValuation extends Carfax
{
    protected function requestValuationAPI(array $data, string $access_token): ?array
    {

        $result = '{
	"responseData": {
		"vehicleInformation": {
			"vin": "1FTFW1E81PFC44695",
			"year": 2023,
			"make": "Ford X",
			"model": "F-150 Dummy",
			"trim": "LARIAT",
			"driveTrain": "4RD",
			"engine": "3.5L Turbo V6",
			"fuelType": "Gas",
			"transmission": "Automatic",
			"odometer": 116917,
			"postalCode": "M5E 1W7",
			"province": "ON",
			"hasBCRegistration": false
		},
		"marketRangeValuations": [
			{
				"minValuation": 43514,
				"maxValuation": 48031,
				"valuationType": 1,
				"isFloorValue": false
			},
			{
				"minValuation": 38525,
				"maxValuation": 42795,
				"valuationType": 2,
				"isFloorValue": false
			},
			{
				"minValuation": 46180,
				"maxValuation": 50823,
				"valuationType": 4,
				"isFloorValue": false
			},
			{
				"minValuation": 37796,
				"maxValuation": 42028,
				"valuationType": 5,
				"isFloorValue": false
			}
		],
		"msrp": 74950
	},
	"resultCode": 1,
	"resultMessage": "Successful"
}';

        $result = json_decode($result, true);

        if (intval($result['resultCode']) !== 1) {
            $result['errors'] = $result['resultMessage'];
        }

        return $result;
    }
}
