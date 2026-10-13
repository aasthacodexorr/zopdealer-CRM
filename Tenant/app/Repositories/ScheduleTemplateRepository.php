<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\ScheduleTemplateModel;

/**
 * ScheduleTemplateRepository CRUD and pagination for the Schedule Template
 */
class ScheduleTemplateRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new ScheduleTemplateModel());
        $this->entity = 'Tenant\app\Entities\ScheduleTemplate';
        $this->entityLabel = 'Schedule Template';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'schedule_template_id';
    }

    /**
     * @method prepareDataForFilter : prepares data received.
     * @param $data : $data received.
     * @return $query : String
     */
    public function prepareDataForFilter($data)
    {
        $query = [];
        if (count($data) > 0) {
            // var_dump($data);
            foreach ($data as $key => $value) {
                if ($key == 'zm_module' || $key == 'zm_module_id') {
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
