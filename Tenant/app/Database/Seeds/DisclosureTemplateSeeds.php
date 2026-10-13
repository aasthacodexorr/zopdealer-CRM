<?php

namespace Tenant\app\Database\Seeds;

use Tenant\app\Models\DisclosureTemplateModel;

class DisclosureTemplateSeeds extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $arrays = [
            [
                'id' => 1,
                'description' => 'This vehicle has had a previous carfax accident claim of $',
                'favourite' => 0
            ],
            [
                'id' => 2,
                'description' => 'This vehicle has had a previous accident claim (not listed on carfax) of $',
                'favourite' => 0
            ],
            [
                'id' => 3,
                'description' => 'The total distance this vehicle has driven is unkown and may be substantially higher than the reading shown on the odometer',
                'favourite' => 0
            ],
            [
                'id' => 4,
                'description' => 'The total distance this vehicle has driven as of ___________________ is _________________ The true total distance the vehicle has driven is believed to be higher',
                'favourite' => 0
            ],
            [
                'id' => 5,
                'description' => 'This vehicles odometer is in miles',
                'favourite' => 0
            ],
            [
                'id' => 6,
                'description' => 'This vehicle is being sold “as is” not in road worthy condition, with no warranty or guarantee. The vehicle will require significant repairs, not limited to only the following:- Anti -lock braking system(possibly not operational)- Airbags(possibly missing or not operational)- Engine, transmission and or powertrain-Subframe- Suspension- Computer equipment- Electrical system- Fuel operation system-Air conditioning- Windshield- Brakes- TiresWe recommend the vehicle be towed away at the buyers expense',
                'favourite' => 0
            ],
            [
                'id' => 7,
                'description' => 'This vehicle has experienced aftermarket, non-factory paintwork',
                'favourite' => 0
            ],
            [
                'id' => 8,
                'description' => 'This vehicle has a serious outstanding recall needed to be performed by the manufacturer',
                'favourite' => 0
            ],
            [
                'id' => 9,
                'description' => 'This vehicle does not have air conditioning',
                'favourite' => 0
            ],
            [
                'id' => 10,
                'description' => 'This vehicle smells like tobacco smoke',
                'favourite' => 0
            ],
            [
                'id' => 11,
                'description' => 'This vehicle has a very apparent odour',
                'favourite' => 0
            ],
            [
                'id' => 12,
                'description' => 'This vehicle was previously a plow truck',
                'favourite' => 0
            ],
            [
                'id' => 13,
                'description' => 'This vehicle requires repairs to its emission system',
                'favourite' => 0
            ],
            [
                'id' => 14,
                'description' => 'This vehicle had a previous accident or incident estimated under $3,000 not listed on the carfax History Report',
                'favourite' => 0
            ],
            [
                'id' => 15,
                'description' => 'This vehicle had multiple previous accident or incidents estimated under $3,000 not listed on the carfax History Report.',
                'favourite' => 0
            ],
            [
                'id' => 16,
                'description' => 'This vehicle had multiple previous accidents or incidents listed on the carfax History Report for $ and $ and $',
                'favourite' => 0
            ],
            [
                'id' => 17,
                'description' => 'The maximum distance that will be shown on the odometer at time of delivery will be',
                'favourite' => 0
            ],
            [
                'id' => 18,
                'description' => 'This vehicle is badged/marked/indicated improperly. It is a different trim level then it appears',
                'favourite' => 0
            ],
            [
                'id' => 19,
                'description' => 'This vehicle has on outstanding recall. Customer to perform. Recall #',
                'favourite' => 0
            ],
            [
                'id' => 20,
                'description' => "Buyer Beware. This vehicle is being sold 'AS IS' for parts only. NO WARRANTY, NO GUARANTEE. Further, the vehicle is not in road worthy condition, and should be towed off the premise.",
                'favourite' => 0
            ],
            [
                'id' => 21,
                'description' => "This vehicle was converted and now uses propane",
                'favourite' => 0
            ],
            [
                'id' => 22,
                'description' => 'This vehicle was converted and now uses natural gas',
                'favourite' => 0
            ],
            [
                'id' => 23,
                'description' => 'This vehicles vin plate is missing',
                'favourite' => 0
            ],
            [
                'id' => 24,
                'description' => 'This vehicles vin door sticker is missing',
                'favourite' => 0
            ],
            [
                'id' => 25,
                'description' => 'This vehicle needs substantial repairs in excess of $750 - ',
                'favourite' => 0
            ]
        ];

        foreach ($arrays as $array) {
            $disclosureTemlateModel = new DisclosureTemplateModel();
            $disclosureTemlateModel->protect(false);
            $disclosureTemlateModel->ignore(true)->insert($array);
            $disclosureTemlateModel->protect(true);
        }
    }
}
