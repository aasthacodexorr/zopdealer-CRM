<?php

namespace App\Repositories;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\API\ResponseTrait;
use  CodeIgniter\HTTP\Response;
use CodeIgniter\Model;

class BaseRepository extends Response
{
    use ResponseTrait;

    protected $request;
    
    /**
     * model This represents the model coupled with the repository instance.
     *
     * @var Model
     */
    public $model;
    
    /**
     * __construct Base Repository accepts the parameter of the Model that is complemented by this particular repository.
     *
     * @param  Model $model
     * @return void
     */
    public function __construct(Model $model)
    {
            $this->request = \Config\Services::request();
            $this->response = \Config\Services::response();
            $this->model = $model;
    }  


    /**
     * Get model This represents the model coupled with the repository instance.
     *
     * @return  Model
     */ 
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set model This represents the model coupled with the repository instance.
     *
     * @param  Model  $model  model This represents the model coupled with the repository instance.
     *
     * @return  self
     */ 
    public function setModel(Model $model)
    {
        $this->model = $model;

        return $this;
    }
}