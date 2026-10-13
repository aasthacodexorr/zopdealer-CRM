<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\AdfleadModel;

class AdfleadRepository extends BaseRepository
{

  protected $_items;

  public function __construct()
  {
    parent::__construct(new AdfleadModel());
  }

  protected function list()
  {
    //return $this->model->orderBy('id', 'DESC')->asArray()->findAll() ;    
    return $this->model->orderBy('id', 'DESC')->findAll();
  }

  public function filterByStatus($status)
  {
    $this->model->where('status', $status);
    return $this;
  }

  public function addSelect($fields = ['*'])
  {
    $this->model->select($fields);
    return $this;
  }

  public function getLeadList()
  {
    return $this->list();
  }
}
