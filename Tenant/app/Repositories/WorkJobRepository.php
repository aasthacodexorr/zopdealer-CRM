<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\WorkJobModel;

/**
 * WorkJobRepository CRUD and pagination for the WorkJob
 */
class WorkJobRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new WorkJobModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'Work Job';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'work_job_id';
    }

    /**
     * @method prepareDataForFilter : prepares data received from the get parameters for filter and search results
     * @param $data : $data received. 
     * @return $query : String
     */

    public function prepareDataForFilter($data)
    {
        $query = [];
        if (count($data) > 0) {
            // var_dump($data);
            foreach ($data as $key => $value) {
                if (
                    $key == 'zm_work_order_id'

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
