<?php

namespace App\Repositories;

use App\Models\WordpressJobModel;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Tenant\app\Entities\DealView;

/**
 * WordpressJobRepository CRUD for wordpressJobView Model
 */
class WordpressJobRepository extends CRUDWithAdvanceRepository
{

    public function __construct()
    {
        parent::__construct(new WordpressJobModel());
        $this->entity = 'Tenant\app\Entities\Notification';
        $this->entityLabel = 'Wordpress Job';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'wordpress_job_id';
    }


    public function createOrUpdate(array $data): array
    {
        try {

            if (empty($data['url'])) {
                throw new \Exception("URL is Missing.", 1);
            }

            $instance = $this->model->where('url', $data['url'])->asArray()->first();

            if (empty($instance)) {
                return parent::create($data);
            } else {
                return parent::update($instance['id'], $data);
            }
        } catch (\Exception $e) {

            return ["errors" => $e->getMessage()];
        }
    }


    public function scheduleQueueBasedOnColumn(string $column)
    {

        $current = Carbon::now('UTC');
        $future_minutes = $current->copy()->addMinutes(0);
        $this->model->where($this->model->table . '.' . $column . " <= '" . $future_minutes . "' ");
        return parent::index(null, null, null, null, 120, '{"' . $column . '" : "ASC"}', false);
    }
}
