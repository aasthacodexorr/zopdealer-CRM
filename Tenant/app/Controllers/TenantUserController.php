<?php

namespace Tenant\app\Controllers;

use App\Controllers\BaseController;
use App\Exceptions\ModelNotFoundException;
use Tenant\app\Repositories\TenantUserMetaDataRepository;
use Tenant\app\Repositories\ActivityViewRepository;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\CraigslistViewRepository;
use Tenant\app\Repositories\CustomerReportViewRepository;
use Tenant\app\Repositories\InventoryViewRepository;
use Tenant\app\Repositories\LeadResponseViewRepository;
use Tenant\app\Repositories\VendorViewRepository;
use Tenant\app\Repositories\CustomerViewRepository;
use Tenant\app\Repositories\DealReportViewRepository;
use Tenant\app\Repositories\DealViewRepository;
use Tenant\app\Repositories\DigitalVehicleInspectionViewRepository;
use Tenant\app\Repositories\FacebookMarketPlaceViewRepository;
use Tenant\app\Repositories\InventoryCostReportViewRepository;
use Tenant\app\Repositories\InventoryPurchaseReportViewRepository;
use Tenant\app\Repositories\InventoryReportViewRepository;
use Tenant\app\Repositories\InventoryRepository;
use Tenant\app\Repositories\LeadReportViewRepository;
use Tenant\app\Repositories\PaymentReportViewRepository;
use Tenant\app\Repositories\PaymentViewRepository;
use Tenant\app\Repositories\WorkOrderViewRepository;
use Tenant\app\Repositories\ProductViewRepository;
use Tenant\app\Repositories\ReviewFeedbackViewRepository;
use Tenant\app\Repositories\ReviewViewRepository;
use Tenant\app\Repositories\SocialConnectViewRepository;
use Tenant\app\Repositories\VoucherViewRepository;
use Tenant\app\Repositories\TestDriveViewRepository;
use Tenant\app\Repositories\TestDriveCustomerViewRepository;

class TenantUserController extends BaseController
{
    private $module;
    private $key;
    private $module_repository;
    private $configuration_repository;

    private $restricted_module = [
        'payment',
        'craigslist',
        'facebook-marketplace',
        'vendor',
        'deal-report',
        'inventory-report',
        'lead-report',
        'social-connect',
        'review',
        'review-feedback',
        'payment-report',
        'inventory-cost-report',
        'inventory-purchase-report',
        'customer-report'
    ];

    public function __construct()
    {
        $this->module = null;
        $this->key = null;
        $this->configuration_repository = new ConfigurationRepository();
    }


    public function index()
    {
        $tenantUser = new TenantUserMetaDataRepository();
        $tenantUser->setTenantUserModel(service('authPolicy')->email);
        $this->response->setContentType('application/json')->setJSON($tenantUser->userMetaData())->send();
    }


    public function getUserView($module)
    {
        $this->checkIfModuleExist($module);

        if (in_array($this->module, $this->restricted_module)) {
            if (!service('authPolicy')->hasElevatedRoles()) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'You do not have permission to access the resource.'])->send();
                exit;
            }
        }

        /** Get Default User Inventory */
        if (service('authPolicy')->organization == ORGANIZATION) {
            $response = $this->module_repository->result(1);
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $tenantUser = new TenantUserMetaDataRepository();
            $tenantUser->setTenantUserModel(service('authPolicy')->email);
            $metadata = $tenantUser->userMetaData();
            $this->key = isset($metadata[0]->{$this->key}) ? (int) $metadata[0]->{$this->key} : 1;
            $response = null;

            if ($this->module == 'activity') {
                $items = 100;

                if ($this->request->getGet('items')) {
                    $items = $this->request->getGet('items');
                }

                $paginate = false;

                if ($this->request->getGet('paginate')) {
                    $paginate = $this->request->getGet('paginate');
                    $paginate = $paginate == "true" ? true : false;
                }

                if (!service('authPolicy')->hasElevatedRoles()) {
                    $response = $this->module_repository->result($this->key, $items, $paginate, service('authPolicy')->id);
                } else {
                    $response = $this->module_repository->result($this->key, $items, $paginate);
                }
            } else {
                $response = $this->module_repository->result($this->key);
            }

            $this->response->setContentType('application/json')->setJSON($response)->send();
        }
    }

    public function setUserView($module)
    {
        $this->checkIfModuleExist($module);

        if (in_array($this->module, $this->restricted_module)) {
            if (!service('authPolicy')->hasElevatedRoles()) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'You do not have permission to access the resource.'])->send();
                exit;
            }
        }

        if ($this->request->getGet('id') && service('authPolicy')->organization != ORGANIZATION) {
            $id = $this->request->getGet('id');
            $tenantUser = new TenantUserMetaDataRepository();
            $tenantUser->setTenantUserModel(service('authPolicy')->email);
            $tenantUser->setUserMetaData([$this->key => $id]);
        } else {
            $this->response->setContentType('application/json')->setStatusCode(400)->setJSON(['errors' => 'Paramter ID not found.']);
        }
    }

    public function create()
    {
        if (service('authPolicy')->isSuperOwner() || service('authPolicy')->hasOwnerRole()) {
            $validated = $this->validate([
                'name'  => [
                    "label" => 'Name',
                    "rules" => 'required|alpha_numeric_punct'
                ],
                'email'  => [
                    "label" => 'Email',
                    "rules" => 'required|valid_email|is_unique[users.email,id,{id}]'
                ],
                'password' => [
                    'label' => 'Password',
                    'rules' => 'required|min_length[6]',
                ],
                'confirm_password' => [
                    'label' => 'Confirm Password',
                    'rules' => 'required|matches[password]',
                ],
                'role_id' => [
                    'label' => 'Role',
                    'rules' => 'required|is_natural_no_zero',
                ],
                'title'  => [
                    "label" => 'Title',
                    "rules" => 'permit_empty|alpha_numeric_punct'
                ],
                'registration_number'  => [
                    "label" => 'Title',
                    "rules" => 'permit_empty|alpha_numeric_punct'
                ],
                'communication_phone'  => [
                    "label" => 'Notification Phone Number',
                    "rules" => 'permit_empty|is_natural|exact_length[10]'
                ],
                'communication_email'  => [
                    "label" => 'Notification Email',
                    "rules" => 'permit_empty|valid_email'
                ],
                'lead_auto_assign'  => [
                    "label" => 'Lead Auto Assign',
                    "rules" => 'permit_empty|is_natural'
                ]
            ]);

            if ($validated) {
                try {
                    $db = db_connect();
                    $data = $this->request->getPost();
                    $password = crypt($data['password'], '$2a$10$' . bin2hex(random_bytes(22)) . '$');
                    $db->table('users')->insert(["email" => $data["email"], "password" => $password]);
                    $user_id = $db->insertID();
                    $db->table('user_role')->insert(["user_id" => $user_id, "role_id" => (int)$data["role_id"]]);
                    $user_metadata = [
                        "user_id" => $user_id,
                        "name" => $data["name"],
                        "title" => $data["title"],
                        "phone" => $data["communication_phone"],
                        "email" => $data["communication_email"],
                        "registration_number" => $data['registration_number'],
                        "lead_auto_assign" => $data['lead_auto_assign']
                    ];

                    if (!empty($data["timezone"])) {
                        $user_metadata['timezone'] = $data["timezone"];
                    }
                    $db->table('user_metadata')->insert($user_metadata);
                    $this->response->setContentType('application/json')->setJSON(['success' => 'New user created.'])->send();
                } catch (\Exception $ex) {
                    $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['email' => $ex->getMessage()])->send();
                }
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode(400)->setJSON(['errors' => ERROR_RESOURCE_PERMISSION]);
        }
    }

    public function update()
    {
        $validated = $this->validate([
            'name'  => [
                "label" => 'Name',
                "rules" => 'required|alpha_numeric_punct'
            ],
            'title'  => [
                "label" => 'Title',
                "rules" => 'required|alpha_numeric_punct'
            ],
            'registration_number'  => [
                "label" => 'Registration Number',
                "rules" => 'permit_empty|alpha_numeric_punct'
            ],
            'phone'  => [
                "label" => 'Phone',
                "rules" => 'permit_empty|is_natural|exact_length[10]'
            ],
            'email'  => [
                "label" => 'Email',
                "rules" => 'required|valid_email'
            ],
            'timezone'  => [
                "label" => 'Timezone',
                "rules" => 'required'
            ],
            'lead_auto_assign'  => [
                "label" => 'Lead Auto Assign',
                "rules" => 'permit_empty|is_natural'
            ]
        ]);

        if ($validated) {
            $data = $this->request->getPost();
            $tenantUser = new TenantUserMetaDataRepository();
            $tenantUser->setTenantUserModel(service('authPolicy')->email);
            $tenantUser->setUserMetaData($data);

            $data['success'] = 'User profile updated successfully. Please logout and login again for changes to take effect.';
            $this->response->setContentType('application/json')->setJSON($data)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function deleteUser($user_id)
    {
        if (service('authPolicy')->isSuperOwner() || service('authPolicy')->hasOwnerRole()) {
            $tenantUser = new TenantUserMetaDataRepository();
            $response = $tenantUser->deleteUser($user_id);

            if (isset($response['success'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode(400)->setJSON(['errors' => ERROR_RESOURCE_PERMISSION]);
        }
    }

    public function allTenantUsersBasicInformation()
    {
        $this->response->setContentType('application/json')->setJSON([
            'users' => (new TenantUserMetaDataRepository())->findAllUsers(['user_metadata.user_id', 'user_metadata.name', "roles.name as role"])
        ])->send();
    }

    public function allTenantUsersRestrictedInformation()
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $this->response->setContentType('application/json')->setJSON([
                'users' => (new TenantUserMetaDataRepository())->findAllUsers(['user_metadata.user_id', 'users.email', 'user_metadata.title', 'user_metadata.phone', 'user_metadata.registration_number', 'user_metadata.name', 'user_metadata.lead_auto_assign', 'user_metadata.is_active', "roles.name as role"])
            ])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function getAllUserRoles()
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $this->response->setContentType('application/json')->setJSON([
                'roles' => (new TenantUserMetaDataRepository())->getAllUserRoles()
            ])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }


    public function getUserViewByID($id)
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $tenantUser = new TenantUserMetaDataRepository();
            $response = $tenantUser->getUserMetaDataById($id);
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function updateUserStatusByID($id, $status)
    {
        if (service('authPolicy')->isSuperOwner() || service('authPolicy')->hasOwnerRole()) {
            $tenantUser = new TenantUserMetaDataRepository();
            $tenantUser->updateUserMetaDataByID($id, ['is_active' => $status]);
            $success_message = (int) $status == 0 ? 'User has been blocked' : 'User has been unblocked';
            $data['success'] = $success_message;
            $this->response->setContentType('application/json')->setJSON($data)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }


    public function updateUserMetaDataByID($id)
    {
        if (service('authPolicy')->isSuperOwner() || service('authPolicy')->hasOwnerRole()) {
            $validated = $this->validate([
                'name'  => [
                    "label" => 'Name',
                    "rules" => 'required|alpha_numeric_punct'
                ],
                'title'  => [
                    "label" => 'Title',
                    "rules" => 'required|alpha_numeric_punct'
                ],
                'registration_number'  => [
                    "label" => 'Registration Number',
                    "rules" => 'permit_empty|alpha_numeric_punct'
                ],
                'phone'  => [
                    "label" => 'Phone',
                    "rules" => 'permit_empty|is_natural|exact_length[10]'
                ],
                'email'  => [
                    "label" => 'Email',
                    "rules" => 'required|valid_email'
                ],
                'timezone'  => [
                    "label" => 'Timezone',
                    "rules" => 'required'
                ],
            ]);

            if ($validated) {
                $data = $this->request->getPost();

                if (!empty($data['is_active'])) {
                    unset($data['is_active']);
                }

                $tenantUser = new TenantUserMetaDataRepository();
                $tenantUser->updateUserMetaDataByID($id, $data);

                $data['success'] = 'User profile updated successfully. User needs to logout and login again for changes to take in effect.';
                $this->response->setContentType('application/json')->setJSON($data)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function updateUserRoleByID($id)
    {
        if (service('authPolicy')->isSuperOwner() || service('authPolicy')->hasOwnerRole()) {
            $validated = $this->validate([
                'role_id'  => [
                    "label" => 'Role ID',
                    "rules" => 'required|alpha_numeric_punct'
                ]
            ]);

            if ($validated) {
                $data = $this->request->getPost();
                $tenantUser = new TenantUserMetaDataRepository();
                $tenantUser->updateRoleByUserID($id, $data['role_id']);

                $data['success'] = 'User role updated successfully. User needs to logout and login again for changes to take in effect.';
                $this->response->setContentType('application/json')->setJSON($data)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    private function checkIfModuleExist(String $module)
    {
        try {
            switch ($module) {
                case 'inventory':
                    $this->module_repository = new InventoryViewRepository();
                    $this->module = $module;
                    $this->key = 'inventory_view';
                    break;

                case 'deal':
                    $this->module_repository = new DealViewRepository();
                    if (!$this->isCurrentUserAuthorizedToViewAllDeals()) {
                        $this->module_repository->setEntityAttributeAndFilterValue("salesperson", service('authPolicy')->id);
                    }
                    $this->module = $module;
                    $this->key = 'deal_view';
                    break;

                case 'deal-report':
                    $this->module_repository = new DealReportViewRepository();
                    $this->module = $module;
                    $this->key = 'deal_report_view';
                    break;

                case 'inventory-report':
                    $this->module_repository = new InventoryReportViewRepository();
                    $this->module = $module;
                    $this->key = 'inventory_report_view';
                    break;

                case 'lead-report':
                    $this->module_repository = new LeadReportViewRepository();
                    $this->module = $module;
                    $this->key = 'lead_report_view';
                    break;

                case 'lead-response':
                    $this->module_repository = new LeadResponseViewRepository();
                    if (!$this->isCurrentUserAuthorizedToViewAllLeads()) {
                        $this->module_repository->setEntityAttributeAndFilterValue("assignee", service('authPolicy')->id);
                    }
                    $this->module = $module;
                    $this->key = 'lead_response_view';
                    break;

                case 'vendor':
                    $this->module_repository = new VendorViewRepository();
                    $this->module = $module;
                    $this->key = 'vendor_view';
                    break;

                case 'facebook-marketplace':
                    $this->module_repository = new FacebookMarketPlaceViewRepository();
                    $this->module = $module;
                    $this->key = 'facebook_marketplace_view';
                    break;

                case 'craigslist':
                    $this->module_repository = new CraigslistViewRepository();
                    $this->module = $module;
                    $this->key = 'craigslist_view';
                    break;

                case 'customer':
                    $this->module_repository = new CustomerViewRepository();
                    if (!$this->isCurrentUserAuthorizedToViewAllCustomers()) {
                        $this->module_repository->setEntityAttributeAndFilterValue("assignee", service('authPolicy')->id);
                    }
                    $this->module = $module;
                    $this->key = 'customer_view';
                    break;

                case 'activity':
                    $this->module_repository = new ActivityViewRepository();
                    $this->module = $module;
                    $this->key = 'activity_view';
                    break;

                case 'social-connect':
                    $this->module_repository = new SocialConnectViewRepository();
                    $this->module = $module;
                    $this->key = 'social_connect_view';
                    break;

                case 'payment':
                    $this->module_repository = new PaymentViewRepository();
                    $this->module = $module;
                    $this->key = 'payment_view';
                    break;

                case 'product':
                    $this->module_repository = new ProductViewRepository();
                    $this->module = $module;
                    $this->key = 'product_view';
                    break;

                case 'work-order':
                    $this->module_repository = new WorkOrderViewRepository();
                    $this->module = $module;
                    $this->key = 'work_order_view';
                    break;

                case 'review':
                    $this->module_repository = new ReviewViewRepository();
                    $this->module = $module;
                    $this->key = 'review_view';
                    break;

                case 'review-feedback':
                    $this->module_repository = new ReviewFeedbackViewRepository();
                    $this->module = $module;
                    $this->key = 'review_feedback_view';
                    break;

                case 'payment-report':
                    $this->module_repository = new PaymentReportViewRepository();
                    $this->module = $module;
                    $this->key = 'payment_report_view';
                    break;

                case 'inventory-cost-report':
                    $this->module_repository = new InventoryCostReportViewRepository();
                    $this->module = $module;
                    $this->key = 'inventory_cost_report_view';
                    break;

                case 'inventory-purchase-report':
                    $this->module_repository = new InventoryPurchaseReportViewRepository();
                    $this->module = $module;
                    $this->key = 'inventory_purchase_report_view';
                    break;

                case 'voucher':
                    $this->module_repository = new VoucherViewRepository();
                    $this->module = $module;
                    $this->key = 'voucher_view';
                    break;

                case 'customer-report':
                    $this->module_repository = new CustomerReportViewRepository();
                    $this->module = $module;
                    $this->key = 'customer_report_view';
                    break;

                case 'test-drive':
                    $this->module_repository = new TestDriveViewRepository();
                    $this->module = $module;
                    $this->key = 'test_drive_view';
                    break;

                case 'test-drives-customer':
                    $this->module_repository = new TestDriveCustomerViewRepository();
                    $this->module = $module;
                    $this->key = 'test_drives_customers_view';
                    break;

                case 'dvi':
                    $this->module_repository = new DigitalVehicleInspectionViewRepository();
                    $this->module = $module;
                    $this->key = 'digital_vehicle_inspection_view';
                    break;

                default:
                    throw new ModelNotFoundException();
                    break;
            }
        } catch (ModelNotFoundException  $e) {
            echo $e->errorMessage();
        }
    }

    private function isCurrentUserAuthorizedToViewAllDeals()
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            return true;
        }

        $authorize_staff_to_view_all_deals =  $this->configuration_repository->findConfigurationColumnsByKey('authorize_staff_to_view_all_deals', 'value')->value;
        return !empty($authorize_staff_to_view_all_deals);
    }

    private function isCurrentUserAuthorizedToViewAllLeads()
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            return true;
        }

        $authorize_staff_to_view_all_leads =  $this->configuration_repository->findConfigurationColumnsByKey('authorize_staff_to_view_all_leads', 'value')->value;
        return !empty($authorize_staff_to_view_all_leads);
    }

    private function isCurrentUserAuthorizedToViewAllCustomers()
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            return true;
        }

        $authorize_staff_to_view_all_customers =  $this->configuration_repository->findConfigurationColumnsByKey('authorize_staff_to_view_all_customers', 'value')->value;
        return !empty($authorize_staff_to_view_all_customers);
    }
}
