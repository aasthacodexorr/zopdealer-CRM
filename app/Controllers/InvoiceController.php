<?php

namespace App\Controllers;

use App\Models\ClientModel;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\InvoiceRepository;
use Tenant\app\Libraries\Zoho\Api\Invoice as Zinvoice;

/**
 *  Update all invoice based on selection for tracking
 */

class InvoiceController extends BaseController
{

  private $configuraton;
  private $invoice;

  public function __construct()
  {
    $this->configuraton = new ConfigurationRepository();
    $this->invoice = new InvoiceRepository();
  }

  /**
   * update all invoice record in database
   * 
   * @return void
   */
  public function overdueInvoices()
  {
    $clientModel = new ClientModel();
    $clients = $clientModel->asArray()->findAll();

    foreach ($clients as $client) {
      try {
        $db = \Config\Database::connect();
        $db->setDatabase($client['subdomain'] . '_' . $client['client_hash']);
        //echo "Tenant:" . $client['subdomain'] . '_' .  $client['client_hash'] .  PHP_EOL;

        //  Resources Purge
        $status = $this->configuraton->findConfigurationColumnsByKey('show_pastduenotification', ['value']);

        if ($status->value) {
          $invoice = $this->invoice->getSubscriptionHistory();
          if ($invoice == "") continue;
          $code = json_decode($invoice, true);
          $subscriptionIds = array_keys($code);
          $this->invoice->cleanInvoiceHistory();

          foreach ($subscriptionIds as $key => $subscriptionId) {
            $invoiceCall =  new Zinvoice();
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
          $result  = $this->invoice->updateInvoiceHistory($invoiceCollection);
        }
      } catch (\Exception $e) {
        echo $e->getMessage();
      }
    }
  }
}