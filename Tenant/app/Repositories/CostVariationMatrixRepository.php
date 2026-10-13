<?php

namespace Tenant\app\Repositories;


use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\CostVariationMatrixModel;

/**
 * CostVariationMatrixRepository CRUD and pagination for the Cost Variation Matrix
 */
class CostVariationMatrixRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new CostVariationMatrixModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'Cost Variation Matrix';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'cost_variation_matrix_id';
    }

    public function prepareDataForFilter($data)
    {
        $query = [];
        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                if (
                    $key == 'zm_entity' ||  $key == 'zm_entity_id'
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
