<?php

namespace  Tenant\app\Repositories;

use Tenant\app\Repositories\ConfigurationRepository;
use App\Repositories\BaseRepository;
use Tenant\app\Models\InvoiceModel;
use Tenant\app\Entities\Invoice;

class InvoiceRepository extends BaseRepository
{

  protected $_items;

  public function __construct()
  {
    parent::__construct(new InvoiceModel());
  }

  protected function list()
  {
    return $this->model->orderBy('invoice_date', 'DESC')->findAll();
  }

  protected function subscriptionList()
  {
    $configuration = new ConfigurationRepository();
    $syndicationData = $configuration->findConfigurationColumnsByKey('subscription_id', ['value']);
    return $data = $syndicationData->value;
  }

  public function getCustomerIds()
  {
    $configuration = new ConfigurationRepository();
    $syndicationData = $configuration->findConfigurationColumnsByKey('subscription_client_id', ['value']);
    return $data = $syndicationData->value;
  }

  public function updateSubscriptionHistory($data)
  {
    $configuration = new ConfigurationRepository();
    $syndicationData = $configuration->setConfiguration(['subscription_id' => $data]);
    return $syndicationData;
  }

  public function cleanInvoiceHistory()
  {
    $db = \Config\Database::connect();
    $builder = $db->table('invoice');
    $builder->emptyTable('invoice');
  }

  public function updateInvoiceHistory($invoiceData)
  {
    if ($this->model->insertBatch($invoiceData) === false) {
      return $this->model->errors();
    } else {
      return [
        "success" => 'New Record Created'
      ];
    }
  }

  public function addSelect($fields = ['*'])
  {
    $this->model->select($fields);
    return $this;
  }

  public function getInvoiceHistory()
  {
    return $this->list();
  }

  public function getSubscriptionHistory()
  {
    return $this->subscriptionList();
  }

  public function getOverDueInvoiceList()
  {
    return $this->model->where('status','overdue')->orderBy('invoice_date', 'DESC')->findAll();   
  }
  
  public function getShowBilling()
  {
    $configuration = new ConfigurationRepository();
    $syndicationData = $configuration->findConfigurationColumnsByKey('show_billing', ['value']);
    return $data = $syndicationData->value;
  }  

}
