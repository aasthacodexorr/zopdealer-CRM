<?php namespace Tenant\app\Controllers;

use App\Controllers\BaseController;
use Tenant\app\Repositories\InvoiceRepository;
use Tenant\app\Libraries\Zoho\Api\Subscription;
use Tenant\app\Libraries\Zoho\Api\Invoice;

class InvoiceController extends BaseController
{

  const NO_SUBSCRIPTION = "No Active Subscription Found";

  /**
   *  Download invoice
   * 
   * @return void
   */
  
   public function downloadInvoice($invoiceID)
  {
    if (service('authPolicy')->hasOwnerRole() || service('authPolicy')->isSuperOwner()) {
        $invoiceCall =  new Invoice();
        $result = $invoiceCall->getInvoiceById($invoiceID);

      $this->data['url'] = $result->invoice_url;
      $this->data['success'] = "opening in new tab ...";
      $this->response->setContentType('application/json')->setJSON($this->data)->send();

    } else {
      $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
    }
  }

   /**
   *  List all Invoices for client account
   * 
   * @return void
   */

  public function getInvoiceList()
  {
    if ((service('authPolicy')->isSuperOwner()) || (service('authPolicy')->hasOwnerRole())) {
      $invoice = new InvoiceRepository();
      $status = $invoice->getShowBilling();
      if($invoice->getShowBilling()!=1){ 
        $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();  
      }else{      
        $this->data['data']['invoice'] = $invoice->getInvoiceHistory();
        $this->response->setContentType('application/json')->setJSON($this->data)->send();
      }
    } else {
      $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['data' => ERROR_RESOURCE_PERMISSION])->send();
    }
  }

 /**
   *  Update Invoices based on the Subscription ID's (NOT IN USE)
   *  @todo  For future use for listing subscription 
   * 
   * @return void
   */
  public function getSubscription()
  {
    if (service('authPolicy')->hasOwnerRole() || service('authPolicy')->isSuperOwner()) {
      $invoice = new InvoiceRepository();
      $this->data['data']['subscription'] = $invoice->getSubscriptionHistory();
      $this->response->setContentType('application/json')->setJSON($this->data)->send();

    }else {
      $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
    }
  }

  /**
   *  Update Invoices based on the Subscription ID's
   * 
   * @return void
   */

  public function updateInvoiceList()
  {
    if (service('authPolicy')->hasOwnerRole() || service('authPolicy')->isSuperOwner()) {
      $invoiceCollection = [];
      $invoiceRepo = new InvoiceRepository();
      $invoiceHistory = $invoiceRepo->getSubscriptionHistory();
      if($invoiceHistory == ""){
        $this->data['msg'] = self::NO_SUBSCRIPTION;
        $this->response->setContentType('application/json')->setJSON($this->data)->send();
      } 
  
      $code = json_decode($invoiceHistory, true);
      $subscriptionIds = array_keys($code);
      $invoiceRepo->cleanInvoiceHistory();

      foreach ($subscriptionIds as $key => $subscriptionId) {
        $invoiceCall =  new Invoice();
        $result = $invoiceCall->getInvoicesBySubscriptionIds($subscriptionId);

        foreach ($result as $key => $invoice) {
          $invoiceData['invoice_no'] = $invoice->number;
          $invoiceData['invoice_fullid'] = $invoice->invoice_id;
          $invoiceData['amount'] = $invoice->currency_code . ' ' . $invoice->total;
          $invoiceData['status'] = $invoice->status;
          $invoiceData['subscription_no'] = $subscriptionId;
          $invoiceData['invoice_date'] = $invoice->invoice_date;
          $invoiceCollection[] = $invoiceData;
        }
      }
      $result  = $invoiceRepo->updateInvoiceHistory($invoiceCollection);
      $this->data['msg'] = 'Invoice has been updated succesfully.';
      $this->response->setContentType('application/json')->setJSON($this->data)->send();

    } else {
      $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
    }
  }


  /**
   *  Update Subscriptions based on the customer ID
   * 
   * @return void
   */

  public function updateSubscription()
  { 
    if (service('authPolicy')->hasOwnerRole() || service('authPolicy')->isSuperOwner()) {
      $IR = new InvoiceRepository();
      $customerIds = $IR->getCustomerIds(); 
      $customerIds = explode(',', $customerIds);
      $subscriptionData = [];

      foreach ($customerIds as $key => $customerId) {      
        $subscriptionCall =  new Subscription();
        $result = $subscriptionCall->getSubscriptionByCustomerId($customerId);

        foreach ($result as $data => $subscription) {
          $subscriptionData[$subscription->subscription_id] = [
            'plan_name' => $subscription->plan_name,
            'amount' => $subscription->currency_code . ' ' . $subscription->amount,
            'subscription_number' => $subscription->subscription_number,
            'activated_at' => $subscription->activated_at,
            'status' => $subscription->status,
            'last_billing_at' => $subscription->last_billing_at,
          ];
        }
      }

      $this->data = $IR->updateSubscriptionHistory(json_encode($subscriptionData));
      $this->response->setContentType('application/json')->setJSON($this->data)->send();

    } else {
      $this->response->setContentType('application/json')->setStatusCode('200')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
    }
  }

  /**
   *  List all Pending Invoices for client account
   * 
   * @return void
   * 
   */ 

  public function getPendingInvoiceList()
  {
    if ((service('authPolicy')->isSuperOwner()) || (service('authPolicy')->hasOwnerRole())) {
      $invoice = new InvoiceRepository();
      $this->data['data']['invoice'] = $invoice->getOverDueInvoiceList(); 
      $this->response->setContentType('application/json')->setJSON($this->data)->send();

    } else {
      $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['data' => ERROR_RESOURCE_PERMISSION])->send();
    }
  }

}