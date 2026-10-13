<?php

namespace Tenant\app\Controllers;

use App\Controllers\BaseController;
use Config\Cache;

/**
 * UpdateController : Updates the client database for the website.
 */
class UpdateController extends BaseController
{
    public function index()
    {
        try {
            $migrate = \Config\Services::migrations(null, null, false);
            $migrate->setNamespace('Tenant/app')->latest();
            exec("mkdir -m 777 " . ROOTPATH . "/public/upload/" . md5(db_connect()->getDatabase()));
            exec("mkdir -m 777 " . ROOTPATH . "/public/upload/" . md5(db_connect()->getDatabase()) . '/overlay');
            exec("mkdir -m 777 " . ROOTPATH . "/public/image/" . md5(db_connect()->getDatabase()));
        } catch (\Exception $ex) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => $ex->getMessage()])->send()->setHeader('Access-Control-Allow-Origin', '*');
            exit;
        }

        $this->response->setContentType('application/json')->setJSON(["success" => "Migrations Ran Successfully."])->send()->setHeader('Access-Control-Allow-Origin', '*');
    }

    public function seeds()
    {
        try {
            $seeder = \Config\Database::seeder();
            $seeder->call('Tenant\app\Database\Seeds\ConfigurationSeeds');
            $seeder->call('Tenant\app\Database\Seeds\InventoryViewSeeds');
            $seeder->call('Tenant\app\Database\Seeds\LeadResponseViewSeeds');
            $seeder->call('Tenant\app\Database\Seeds\VendorViewSeeds');
            $seeder->call('Tenant\app\Database\Seeds\CustomerViewSeeds');
            $seeder->call('Tenant\app\Database\Seeds\DealViewSeeds');
            $seeder->call('Tenant\app\Database\Seeds\ActivityViewSeeds');
            $seeder->call('Tenant\app\Database\Seeds\DealReportViewSeeds');
            $seeder->call('Tenant\app\Database\Seeds\InventoryReportViewSeeds');
            $seeder->call('Tenant\app\Database\Seeds\LeadReportViewSeeds');
            $seeder->call('Tenant\app\Database\Seeds\FacebookMarketPlaceViewSeeds');
            $seeder->call('Tenant\app\Database\Seeds\CraigslistViewSeeds');
            $seeder->call('Tenant\app\Database\Seeds\SocialConnectViewSeeds');
            $seeder->call('Tenant\app\Database\Seeds\PaymentViewSeeds');
            $seeder->call('Tenant\app\Database\Seeds\ProductViewSeeds');
            $seeder->call('Tenant\app\Database\Seeds\WorkOrderViewSeeds');
            $seeder->call('Tenant\app\Database\Seeds\ReviewViewSeeds');
            $seeder->call('Tenant\app\Database\Seeds\ReviewFeedbackViewSeeds');
            $seeder->call('Tenant\app\Database\Seeds\PaymentReportViewSeeds');
            $seeder->call('Tenant\app\Database\Seeds\InventoryCostReportViewSeeds');
            $seeder->call('Tenant\app\Database\Seeds\InventoryPurchaseReportViewSeeds');
            $seeder->call('Tenant\app\Database\Seeds\VoucherViewSeeds');
            $seeder->call('Tenant\app\Database\Seeds\CustomerReportViewSeeds');
            $seeder->call('Tenant\app\Database\Seeds\DisclosureTemplateSeeds');
            $seeder->call('Tenant\app\Database\Seeds\TestDriveViewSeeds');
            $seeder->call('Tenant\app\Database\Seeds\TestDrivesCustomerViewSeeds');
            $seeder->call('Tenant\app\Database\Seeds\DVIViewSeeds');

        } catch (\Exception $ex) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => $ex->getMessage()])->send()->setHeader('Access-Control-Allow-Origin', '*');
            exit;
        }

        $this->response->setContentType('application/json')->setJSON(["success" => "Seeding has been successfully."])->send()->setHeader('Access-Control-Allow-Origin', '*');
    }

    public function options($any)
    {
        return $this->response->setHeader('Access-Control-Allow-Origin', '*') //for allow any domain, insecure
            ->setHeader('Access-Control-Allow-Headers', '*') //for allow any headers, insecure
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE') //method allowed
            ->setStatusCode(200); //status code
    }
}
