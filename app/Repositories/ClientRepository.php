<?php

namespace App\Repositories;

use App\Models\ClientModel;
use Carbon\Carbon;

/**
 * ClientRepository CRUD for Client Model
 */
class ClientRepository extends CRUDWithAdvanceRepository
{

    public function __construct()
    {
        parent::__construct(new ClientModel());
        $this->entity = 'App\Entities\Client';
        $this->entityLabel = 'Client';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'client_id';
    }

    public function onlyDeleted()
    {
        $this->model->onlyDeleted();
        return $this;
    }

    public function scheduleQueueBasedOnColumn(string $column, $limit = 25)
    {

        $current = Carbon::now('UTC');

        $future_minutes = $current->copy()->addMinutes(0);

        $this->model->where($this->model->table . '.' . $column . " <= '" . $future_minutes . "' ");

        return parent::index(null, null, null, null, $limit, '{"' . $column . '" : "ASC"}', true);
    }
}
