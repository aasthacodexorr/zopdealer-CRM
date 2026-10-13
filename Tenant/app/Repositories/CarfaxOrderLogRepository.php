<?php

namespace Tenant\app\Repositories;

use App\Models\CarfaxOrderLogModel;
use App\Repositories\CRUDWithAdvanceRepository;

class CarfaxOrderLogRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new CarfaxOrderLogModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'Carfax Order Log';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'carfax_order_log_id';
    }


    public function prepareDataForFilter($data)
    {
        $query = [];
        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                if (
                    $key == 'zm_vin' || $key == 'zm_stock_no'

                ) {
                    $single_query = [
                        'header' => null,
                        'column' => str_replace('zm_', '', $key),
                        'join' => 'AND',
                        'comparator' => "is",
                        "type" => "Multi Select",
                        'value_1' => $value,
                        'value_2' => null,
                    ];

                    array_push($query, $single_query);
                }
            }
        }

        return json_encode($query);
    }
}
