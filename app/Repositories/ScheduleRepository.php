<?php

namespace App\Repositories;

use App\Libraries\Schedule\ScheduleConstant;
use App\Models\ScheduleModel;
use Carbon\Carbon;

class ScheduleRepository extends CRUDWithAdvanceRepository
{

    public function __construct()
    {
        parent::__construct(new ScheduleModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'Schedule';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'schedule_id';
    }

    public function activeSchedule()
    {

        $current = Carbon::now('UTC');

        $future_minutes = $current->copy()->addMinutes(0);

        $this->model->where($this->model->table . '.' . 'schedule' . " <= '" . $future_minutes . "' ");

        $conditions = [
            [
                'header' => null,
                'column' => 'status',
                'join' => 'AND',
                'comparator' => "is",
                "type" => "Single Select",
                'value_1' => ScheduleConstant::ACTIVE,
                'value_2' => null
            ]
        ];

        return parent::index(null, null, null, json_encode($conditions), 20, null, false);
    }

    public function createOrUpdate(array $data): array
    {
        try {
            if (empty($data['unique_index'])) {
                throw new \Exception("Unique Index is missing.", 1);
            }

            $instance = $this->model->where('unique_index', $data['unique_index'])->asArray()->first();

            if (empty($instance)) {
                return parent::create($data);
            } else {
                return parent::update($instance['id'], $data);
            }
        } catch (\Exception $e) {
            return ["errors" => $e->getMessage()];
        }
    }

    public function convertTimeToUTC($current_datetime = NULL, $timezone = 'UTC', $increment = '')
    {
        if (empty($current_datetime)) {
            $current_datetime = Carbon::now($timezone);
        } else {
            $current_datetime = Carbon::parse($current_datetime, $timezone);
        }

        switch ($increment) {
            case ScheduleConstant::DAILY:
                $current_datetime->addDay();
                break;

            case ScheduleConstant::WEEKLY:
                $current_datetime->addWeek();
                break;

            case ScheduleConstant::MONTHLY:
                $current_datetime->addMonth();
                break;

            case ScheduleConstant::QUARTERLY:
                $current_datetime->addQuarter();
                break;

            case ScheduleConstant::YEARLY:
                $current_datetime->addYear();
                break;

            default:
                break;
        }


        return $current_datetime->tz('UTC');
    }

    public function deleteWithoutKey()
    {
        $this->model->delete();
    }
}
