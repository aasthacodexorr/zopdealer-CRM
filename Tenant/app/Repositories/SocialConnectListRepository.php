<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\SocialConnectListModel;
use Carbon\Carbon;

/**
 * SocialConnectRepository CRUD and pagination for the Facebook Connect Logs
 */
class SocialConnectListRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new SocialConnectListModel());
    }

    public function findAll(int $limit = 0, int $offset = 0)
    {
        return $this->model->asArray()->findAll( $limit,$offset);
    }

    public function getYesterdayInventory()
    {
        $today =   Carbon::parse(Carbon::now()->subDays(1));
        $this->model->where("DATE({$this->model->table}.created_at) = DATE('{$today}')", null, false);
        return $this;
    }

}
