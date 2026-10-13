<?php

namespace Tenant\app\Repositories;

use App\Exceptions\ModelNotFoundException;
use App\Repositories\CRUDWithAdvanceRepository;
use Carbon\Carbon;
use Tenant\app\Models\EmailNotificationModel;
use Tenant\app\Models\NotificationModel;
use Tenant\app\Models\PushNotificationModel;
use Tenant\app\Models\SMSNotificationModel;

/**
 * NotificationRepository CRUD and pagination for the Notification
 */
class NotificationRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new NotificationModel());
        $this->entity = 'Tenant\app\Entities\Notification';
        $this->entityLabel = 'Notification';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'notification_id';
    }


    /**
     * switchStateNotification This method sets the state of model
     *
     * @param  mixed $model
     * @return NotificationRepository
     */
    public function switchStateNotification($state): NotificationRepository
    {
        try {
            switch ($state) {
                case 'email':
                    $this->model = new EmailNotificationModel();
                    $this->entityLabel = 'Email Notification';
                    $this->id_field = 'email_id';
                    break;

                case 'push':
                    $this->model = new PushNotificationModel();
                    $this->entityLabel = 'Push Notification';
                    $this->id_field = 'push_id';
                    break;

                case 'sms':
                    $this->model = new SMSNotificationModel();
                    $this->entityLabel = 'SMS Notification';
                    $this->id_field = 'sms_id';
                    break;

                default:
                    throw new ModelNotFoundException();
                    break;
            }
        } catch (ModelNotFoundException $e) {
            echo $e->errorMessage();
        }

        $this->createSuccessMessage = $this->entityLabel . ' has been added.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        return $this;
    }

    /**
     * @depreciated This function is not in use anymore.
     * unSentNotifications This method prepares data for the notifications that are supposed to be sent.
     */
    public function unSentNotifications()
    {
        $current = Carbon::now('UTC');

        $minutes_earlier = $current->copy()->subMinutes(15);
        $minutes_after = $current->copy()->addMinutes(2);

        $this->model->where($this->model->table . '.' . 'schedule' . " <= '" . $minutes_after . "' and " . $this->model->table . '.' . 'schedule' . " >= '" . $minutes_earlier . "' ");

        $conditions = [
            [
                'header' => null,
                'column' => 'status',
                'join' => 'AND',
                'comparator' => "is",
                "type" => "Single Select",
                'value_1' => 'PENDING',
                'value_2' => null
            ]
        ];

        return parent::index(null, null, null, json_encode($conditions), 20, null, false);
    }

    public function createOrUpdate(array $data): array
    {
        try {
            if (empty($data['identifier'])) {
                throw new \Exception("Identifier is missing.", 1);
            }

            $instance = $this->model->where('identifier', $data['identifier'])->asArray()->first();

            if (empty($instance)) {
                return parent::create($data);
            } else {
                return parent::update($instance['id'], $data);
            }
        } catch (\Exception $e) {
            return ["errors" => $e->getMessage()];
        }
    }
}
