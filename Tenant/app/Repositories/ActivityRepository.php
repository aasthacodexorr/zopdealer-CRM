<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Carbon\Carbon;
use Tenant\app\Models\ActivityModel;

/**
 * ActivityRepository CRUD and pagination for the Activity
 */
class ActivityRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new ActivityModel());
        $this->entity = 'Tenant\app\Entities\Activity';
        $this->entityLabel = 'Activity';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'activity_id';
    }


    public function activitiesReadyForReminder()
    {

        $current = Carbon::now('UTC');

        $minutes_earlier = $current->copy()->subMinutes(15);
        $minutes_after = $current->copy()->addMinutes(2);

        $this->model->where($this->model->table . '.' . 'notification_date' . " <= '" . $minutes_after . "' and " . $this->model->table . '.' . 'notification_date' . " >= '" . $minutes_earlier . "' ");

        $conditions = [
            [
                'header' => null,
                'column' => 'notify',
                'join' => 'AND',
                'comparator' => "is",
                "type" => "Single Select",
                'value_1' => '1',
                'value_2' => null
            ],
            [
                'header' => null,
                'column' => 'assignee',
                'join' => 'AND',
                'comparator' => "is not empty",
                "type" => "Null Comparision",
                'value_1' => '',
                'value_2' => null
            ]
        ];

        return parent::index(null, null, null, json_encode($conditions), 20, null, false);
    }

    /**
     * @method prepareDataForFilter : prepares data recieved from the website to make search consistent.
     * @param $data : $data recieved.
     * @return $query : String
     */

    public function prepareDataForFilter($data)
    {
        $query = [];
        if (count($data) > 0) {
             //var_dump($data);
            foreach ($data as $key => $value) {
                if (
                    $key == 'zm_assignee' || $key == 'zm_status' ||
                    $key == 'zm_priority' || $key == 'zm_module' ||
                    $key == 'zm_module_id' || $key == 'zm_activity_type'

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

            if (!empty($data['zm_date_start']) && !empty($data['zm_date_end'])) {
                $single_query = [
                    'header' => null,
                    'column' => "start_date",
                    'join' => 'AND',
                    'comparator' => "between",
                    "type" => "Date Comparision",
                    'value_1' => array_shift($data['zm_date_start']),
                    'value_2' => array_shift($data['zm_date_end']),
                ];
                array_push($query, $single_query);
            }
        }

        return json_encode($query);
    }

    public function orPublicActivity()
    {
        $this->model->orWhere($this->model->table . '.' . 'is_private', "0");
        return $this;
    }
}
