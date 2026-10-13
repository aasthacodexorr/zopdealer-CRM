<?php

namespace Tenant\Config;

// Create a new instance of our RouteCollection class.
$client_routes = \Config\Services::routes(true);
$client_routes->setTranslateURIDashes(false);
$client_routes->set404Override();
$client_routes->setAutoRoute(true);

$client_routes->setDefaultNamespace('Tenant\app\Controllers');


$client_routes->group('api', function ($client_routes) {
    $client_routes->group('business', ['filter' => 'auth'], function ($client_routes) {
        $client_routes->post('account/logo', 'TenantAccountController::changeLogo');
        $client_routes->post('account/request/close', 'TenantAccountController::sendCloseAccountRequest');

        $client_routes->add('dashboard', 'DashboardController::index');
        $client_routes->add('inventory', 'InventoryController::index');
        $client_routes->get('inventory/search-index/update', 'InventoryController::updateSearchIndex');
        $client_routes->post('inventory/create', 'InventoryController::create');
        $client_routes->get('inventory/show/(:alphanum)', 'InventoryController::show/$1');
        $client_routes->post('inventory/update/(:alphanum)', 'InventoryController::update/$1');
        $client_routes->post('inventory/updates', 'InventoryController::updates');
        $client_routes->post('inventory/partial/update', 'InventoryController::partialUpdate');
        $client_routes->post('inventory/pricing/updates', 'InventoryController::pricingBulkUpdates');
        $client_routes->post('inventory/description/updates', 'InventoryController::descriptionBulkUpdates');
        $client_routes->post('inventory/syndication/updates', 'InventoryController::bulkSyndicationUpdates');
        $client_routes->post('inventory/deletes', 'InventoryController::multipleDeletes');
        $client_routes->post('inventory/purge', 'InventoryController::purgeAllInventory');
        $client_routes->post('inventory/delete/purge', 'InventoryController::purgeDelete');
        $client_routes->post('inventory/assets/purge', 'InventoryController::purgeInventoryAssets');
        $client_routes->post('inventory/status/update/sold', 'InventoryController::markAllInventoriesAsSold');
        $client_routes->post('inventory/status/update/unpublished', 'InventoryController::markAllInventoriesAsUnpublished');
        $client_routes->add('vehicles/lookup/trim_id/(:any)', 'InventoryController::getVehicleInformationByTrimID/$1');
        $client_routes->add('vehicles/lookup/vin/(:alphanum)', 'InventoryController::getVehicleInformationByVIN/$1');
        $client_routes->add('vehicles/(:alphanum)', 'InventoryController::getVehicleInformationByVIN/$1');
        $client_routes->get('inventories/views/result/(:alphanum)', 'InventoryViewController::result/$1');
        $client_routes->presenter('inventories/views', ['controller' => 'InventoryViewController']);
        $client_routes->get('inventory/sticker/(:alphanum)', 'InventoryController::sticker/$1');
        $client_routes->get('inventory/sticker/v2/(:alphanum)', 'InventoryController::stickerV2/$1');
        $client_routes->get('inventory/print/inventory-recap/(:alphanum)', 'InventoryController::printInventoryRecap/$1');
        $client_routes->post('inventory/import', 'InventoryImportController::completeInventoryEntity');

        $client_routes->addPlaceholder('inventory_pdf', '(?i)test-drive|public-purchase|dealer-purchase|vehicle-disclosures|internal-inspection|buyers-guide');
        $client_routes->get('inventory/pdf/(:inventory_pdf)/generate/(:alphanum)', 'InventoryController::generatePDF/$1/$2');

        $client_routes->addPlaceholder('inventory_parser_source', '(?i)eblock|openlane|adesa|iaa|traderev|otolane');
        $client_routes->post('inventory/parser/pdf/(:inventory_parser_source)', 'InventoryController::parsePDF/$1', ['filter' => 'protected-pdf-parser-throttle']);
        $client_routes->post('inventory/vehicle-description-rewriter', 'InventoryController::vehicleDescriptionReWriter', ['filter' => 'protected-vehicle-description-rewriter-throttle']);

        /** Image Upload */
        $client_routes->get('inventory/image/(:alphanum)', 'InventoryController::imageList/$1');
        $client_routes->post('inventory/image/sort/(:alphanum)', 'InventoryController::sortImageRank/$1');
        $client_routes->post('inventory/image/upload/(:alphanum)', 'InventoryController::saveImage/$1');
        $client_routes->post('inventory/image/delete/(:alphanum)', 'InventoryController::deleteImages/$1');
        $client_routes->post('inventory/image/active/(:alphanum)', 'InventoryController::changeImageActiveState/$1');
        $client_routes->get('inventory/image/zip/(:alphanum)', 'InventoryController::zipWebsiteImages/$1');


        /** File Upload */
        $client_routes->get('inventory/file/(:alphanum)', 'InventoryController::listFile/$1');
        $client_routes->post('inventory/file/upload/(:alphanum)', 'InventoryController::saveFile/$1');
        $client_routes->post('inventory/file/delete/(:alphanum)', 'InventoryController::deleteFile/$1');

        /** Inventory Purchase  */
        $client_routes->get('inventory/(:alphanum)/purchase/show/(:alphanum)', 'InventoryPurchaseController::show/$1/$2', ['filter' => 'auth-elevated-access']);
        $client_routes->post('inventory/(:alphanum)/purchase/', 'InventoryPurchaseController::create/$1/$2', ['filter' => 'auth-elevated-access']);
        $client_routes->get('inventory/(:alphanum)/purchase/', 'InventoryPurchaseController::index/$1', ['filter' => 'auth-elevated-access']);
        $client_routes->post('inventory/(:alphanum)/purchase/update/(:alphanum)', 'InventoryPurchaseController::update/$1/$2', ['filter' => 'auth-elevated-access']);
        $client_routes->get('inventory/(:alphanum)/purchase/delete/(:alphanum)', 'InventoryPurchaseController::delete/$1/$2', ['filter' => 'auth-elevated-access']);
        $client_routes->get('inventory-purchases/reports', 'InventoryPurchaseController::reportIndex', ['filter' => 'auth-elevated-access']);
        $client_routes->post('inventory-purchases/reports/(:report_types)/report', 'InventoryPurchaseController::generateReport/$1', ['filter' => 'auth-elevated-access']);

        /** Inventory Costs  */
        $client_routes->get('inventory/(:alphanum)/costs/show/(:alphanum)', 'InventoryCostController::show/$1/$2');
        $client_routes->post('inventory/(:alphanum)/costs/', 'InventoryCostController::create/$1/$2');
        $client_routes->get('inventory/(:alphanum)/costs/', 'InventoryCostController::index/$1');
        $client_routes->post('inventory/(:alphanum)/costs/update/(:alphanum)', 'InventoryCostController::update/$1/$2');
        $client_routes->get('inventory/(:alphanum)/costs/delete/(:alphanum)', 'InventoryCostController::delete/$1/$2');
        $client_routes->get('inventory-costs/reports', 'InventoryCostController::reportIndex');
        $client_routes->post('inventory-costs/reports/(:report_types)/report', 'InventoryCostController::generateReport/$1');

        /** Inventory Vehicle Inspection */
        $client_routes->get('inventory/(:alphanum)/vehicle-inspection/show/(:alphanum)', 'InventoryWebInspectionController::show/$1/$2');
        $client_routes->post('inventory/(:alphanum)/vehicle-inspection', 'InventoryWebInspectionController::create/$1/$2');
        $client_routes->get('inventory/(:alphanum)/vehicle-inspection', 'InventoryWebInspectionController::index/$1');
        $client_routes->post('inventory/(:alphanum)/vehicle-inspection/update/(:alphanum)', 'InventoryWebInspectionController::update/$1/$2');

        $client_routes->addPlaceholder('inventory_common', '(?i)disclosures|internal-inspection|digital-retailing');
        /** Inventory Dependent Routes */
        $client_routes->post('inventory/(:alphanum)/common/digital-retailing', 'InventoryCommonDependentController::updateDigitalRetailing/$1');
        $client_routes->post('inventory/(:alphanum)/common/(:inventory_common)', 'InventoryCommonDependentController::bulkCreateOrUpdate/$1/$2');
        $client_routes->post('inventory/(:alphanum)/common/(:inventory_common)/create', 'InventoryCommonDependentController::create/$1/$2');
        $client_routes->post('inventory/(:alphanum)/common/(:inventory_common)/update/(:alphanum)', 'InventoryCommonDependentController::update/$1/$2/$3');
        $client_routes->get('inventory/(:alphanum)/common/(:inventory_common)/show/(:alphanum)', 'InventoryCommonDependentController::show/$1/$2/$3');
        $client_routes->get('inventory/(:alphanum)/common/(:inventory_common)/delete/(:alphanum)', 'InventoryCommonDependentController::delete/$1/$2/$3');
        $client_routes->get('inventory/(:alphanum)/common/(:inventory_common)', 'InventoryCommonDependentController::index/$1/$2');
        $client_routes->get('inventory/(:alphanum)/common/(:inventory_common)/delete', 'InventoryCommonDependentController::bulkDelete/$1/$2');

        $client_routes->post('inventory/(:alphanum)/notes/create', 'InventoryController::noteCreate/$1');
        $client_routes->get('inventory/(:alphanum)/notes', 'InventoryController::allNotes/$1');
        $client_routes->get('inventory/(:alphanum)/notes/show/(:alphanum)', 'InventoryController::showNote/$1/$2');
        $client_routes->post('inventory/(:alphanum)/notes/update/(:alphanum)', 'InventoryController::updateNote/$1/$2');
        $client_routes->get('inventory/(:alphanum)/notes/delete/(:alphanum)', 'InventoryController::deleteNote/$1/$2');

        $client_routes->get('inventory/(:alphanum)/deals', 'InventoryController::getInventoryDeals/$1');


        /** Vehicle Description Template Module */
        $client_routes->get('vehicle-description-templates', 'VehicleDescriptionTemplateController::index');
        $client_routes->post('vehicle-description-templates/create', 'VehicleDescriptionTemplateController::create');
        $client_routes->post('vehicle-description-templates/update/(:alphanum)', 'VehicleDescriptionTemplateController::update/$1');
        $client_routes->get('vehicle-description-templates/show/(:alphanum)', 'VehicleDescriptionTemplateController::show/$1');
        $client_routes->get('vehicle-description-templates/delete/(:alphanum)', 'VehicleDescriptionTemplateController::delete/$1');

        /** Disclosure Template Module */
        $client_routes->get('disclosure-templates', 'DisclosureTemplateController::index');
        $client_routes->post('disclosure-templates/create', 'DisclosureTemplateController::create');
        $client_routes->post('disclosure-templates/update/(:alphanum)', 'DisclosureTemplateController::update/$1');
        $client_routes->get('disclosure-templates/show/(:alphanum)', 'DisclosureTemplateController::show/$1');
        $client_routes->get('disclosure-templates/delete/(:alphanum)', 'DisclosureTemplateController::delete/$1');

        /*** Customers */
        $client_routes->get('customers', 'CustomerController::index');
        $client_routes->post('customers/create', 'CustomerController::create');
        $client_routes->post('customers/update/(:alphanum)', 'CustomerController::update/$1');
        $client_routes->get('customers/show/(:alphanum)', 'CustomerController::show/$1');

        $client_routes->addPlaceholder('customer_pdf', '(?i)test-drive|credit-application');
        $client_routes->get('customers/pdf/(:customer_pdf)/generate/(:alphanum)', 'CustomerController::generatePDF/$1/$2');
        $client_routes->post('customers/deletes', 'CustomerController::multipleDeletes', ['filter' => 'auth-elevated-access']);

        $client_routes->get('customers/reports', 'CustomerController::reportIndex', ['filter' => 'auth-elevated-access']);
        $client_routes->post('customers/reports/(:report_types)/report', 'CustomerController::generateReport/$1', ['filter' => 'auth-elevated-access']);


        $client_routes->post('customers/(:alphanum)/notes/create', 'CustomerController::noteCreate/$1');
        $client_routes->get('customers/(:alphanum)/notes', 'CustomerController::allNotes/$1');
        $client_routes->get('customers/(:alphanum)/notes/show/(:alphanum)', 'CustomerController::showNote/$1/$2');
        $client_routes->post('customers/(:alphanum)/notes/update/(:alphanum)', 'CustomerController::updateNote/$1/$2');
        $client_routes->get('customers/(:alphanum)/notes/delete/(:alphanum)', 'CustomerController::deleteNote/$1/$2');

        $client_routes->get('customers/(:alphanum)/deals', 'CustomerController::getCustomerDeals/$1');

        $client_routes->presenter('customers/views', ['controller' => 'CustomerViewController']);

        /** Customers Credit Application */
        $client_routes->get('customers/(:alphanum)/credit-application/show/(:alphanum)', 'CustomerCreditApplicationController::show/$1/$2');
        $client_routes->post('customers/(:alphanum)/credit-application/create', 'CustomerCreditApplicationController::create/$1/$2');
        $client_routes->get('customers/(:alphanum)/credit-application', 'CustomerCreditApplicationController::index/$1');
        $client_routes->post('customers/(:alphanum)/credit-application/update/(:alphanum)', 'CustomerCreditApplicationController::update/$1/$2');
        $client_routes->get('customers/(:alphanum)/credit-application/delete/(:alphanum)', 'CustomerCreditApplicationController::delete/$1/$2');

        /** Customer Insurance  */
        $client_routes->get('customers/(:alphanum)/insurance/show/(:alphanum)', 'CustomerInsuranceController::show/$1/$2');
        $client_routes->post('customers/(:alphanum)/insurance', 'CustomerInsuranceController::create/$1/$2');
        $client_routes->get('customers/(:alphanum)/insurance', 'CustomerInsuranceController::index/$1');
        $client_routes->post('customers/(:alphanum)/insurance/update/(:alphanum)', 'CustomerInsuranceController::update/$1/$2');
        $client_routes->get('customers/(:alphanum)/insurance/delete/(:alphanum)', 'CustomerInsuranceController::delete/$1/$2');

        /** Location */
        $client_routes->get('location', 'LocationController::index');
        $client_routes->get('location/show/(:alphanum)', 'LocationController::show/$1');
        $client_routes->post('location/find-by-name', 'LocationController::findByLocationNameOrDefault');
        $client_routes->post('location/create', 'LocationController::create');
        $client_routes->post('location/update/(:alphanum)', 'LocationController::update/$1');
        $client_routes->get('location/active', 'LocationController::getAllActiveLocation');
        $client_routes->post('location/active/(:alphanum)', 'LocationController::changeActivation/$1');
        $client_routes->post('location/default/(:alphanum)', 'LocationController::changeDefault/$1');
        //   $client_routes->get('location/delete/(:alphanum)', 'LocationController::delete/$1');


        /** Update The Tenant Database */
        $client_routes->get('update', 'UpdateController::index');
        $client_routes->get('seeds', 'UpdateController::seeds');

        /*** Image Controller */
        $client_routes->get('image', 'ImageController::index');
        $client_routes->post('image/create', 'ImageController::create');
        $client_routes->get('image/delete/(:alphanum)', 'ImageController::delete/$1');
        $client_routes->post('image/active/(:alphanum)', 'ImageController::changeActivation/$1');
        $client_routes->get('image/placeholder', 'ImageController::getPlaceholder');
        $client_routes->get('image/overlay/process', 'ImageController::processUnsavedOverlayImages');
        $client_routes->options('image/overlay/process', 'UpdateController::options/$1');

        /** Configurations   */
        $client_routes->get('configurations/(:segment)', 'ConfigurationController::index/$1');
        $client_routes->get('configurations/key/(:segment)', 'ConfigurationController::getConfigurationByKey/$1');
        $client_routes->get('configurations/active/(:segment)', 'ConfigurationController::activeConfiguration/$1');

        $client_routes->post('configurations', 'ConfigurationController::update');

        $client_routes->group('user', function ($client_routes) {
            $client_routes->get('/', 'TenantUserController::index');
            $client_routes->get('view/(:alphanum)', 'TenantUserController::getUserViewByID/$1');
            $client_routes->get('update/(:alphanum)/status/(:num)', 'TenantUserController::updateUserStatusByID/$1/$2');
            $client_routes->post('update/(:alphanum)', 'TenantUserController::updateUserMetaDataByID/$1');
            $client_routes->get('delete/(:alphanum)', 'TenantUserController::deleteUser/$1');
            $client_routes->post('role/update/(:alphanum)', 'TenantUserController::updateUserRoleByID/$1');
            $client_routes->post('create', 'TenantUserController::create');
            $client_routes->post('/', 'TenantUserController::update');
            $client_routes->get('all', 'TenantUserController::allTenantUsersBasicInformation');
            $client_routes->get('list', 'TenantUserController::allTenantUsersRestrictedInformation');
            $client_routes->get('roles', 'TenantUserController::getAllUserRoles');
        });

        $client_routes->addPlaceholder('module_placeholders', '(?i)craigslist|facebook-marketplace|inventory|deal|deal-report|lead-response|vendor|customer|activity|inventory-report|lead-report|social-connect|payment|product|work-order|review|review-feedback|payment-report|inventory-cost-report|inventory-purchase-report|voucher|customer-report|test-drive|test-drives-customer|dvi');
        $client_routes->get('user/(:module_placeholders)', 'TenantUserController::getUserView/$1', ['filter' => 'protected-api-throttle']);
        $client_routes->get('user/(:module_placeholders)/update', 'TenantUserController::setUserView/$1');

        $client_routes->get('leads/responses', 'LeadResponseController::index');
        $client_routes->add('leads/responses/create', 'LeadResponseController::create');
        $client_routes->get('leads/responses/show/(:alphanum)', 'LeadResponseController::show/$1');
        $client_routes->add('leads/responses/update/(:alphanum)', 'LeadResponseController::update/$1');
        $client_routes->post('leads/responses/updates', 'LeadResponseController::updates', ['filter' => 'auth-elevated-access']);
        $client_routes->post('leads/responses/partial/update', 'LeadResponseController::partialUpdate');
        $client_routes->add('leads/responses/convert/(:alphanum)/customers', 'LeadResponseController::convertLead/$1');
        $client_routes->post('leads/responses/(:alphanum)/notes/create', 'LeadResponseController::noteCreate/$1');
        $client_routes->get('leads/responses/(:alphanum)/notes', 'LeadResponseController::allNotes/$1');
        $client_routes->get('leads/responses/(:alphanum)/notes/show/(:alphanum)', 'LeadResponseController::showNote/$1/$2');
        $client_routes->post('leads/responses/(:alphanum)/notes/update/(:alphanum)', 'LeadResponseController::updateNote/$1/$2');
        $client_routes->get('leads/responses/(:alphanum)/notes/delete/(:alphanum)', 'LeadResponseController::deleteNote/$1/$2');
        $client_routes->get('leads/responses/views/result/(:alphanum)', 'LeadResponseViewController::result/$1');
        $client_routes->get('leads/responses/(:alphanum)/files', 'LeadResponseController::allFiles/$1');
        $client_routes->presenter('leads/responses/views', ['controller' => 'LeadResponseViewController']);
        $client_routes->get('leads/responses/filters', 'LeadResponseController::filters');

        $client_routes->addPlaceholder('lead_response_pdf', '(?i)test-drive');
        $client_routes->get('leads/responses/pdf/(:lead_response_pdf)/generate/(:alphanum)', 'LeadResponseController::generatePDF/$1/$2');

        /** Vendor Module */
        $client_routes->get('vendors', 'VendorController::index');
        $client_routes->post('vendors/create', 'VendorController::create', ['filter' => 'auth-elevated-access']);
        $client_routes->post('vendors/update/(:alphanum)', 'VendorController::update/$1', ['filter' => 'auth-elevated-access']);
        $client_routes->get('vendors/show/(:alphanum)', 'VendorController::show/$1', ['filter' => 'auth-elevated-access']);
        $client_routes->add('vendors/convert/(:alphanum)/customers', 'VendorController::convertVendor/$1', ['filter' => 'auth-elevated-access']);
        $client_routes->post('vendors/deletes', 'VendorController::multipleDeletes', ['filter' => 'auth-elevated-access']);
        $client_routes->presenter('vendors/views', ['controller' => 'VendorViewController']);

        /** Product Module */
        $client_routes->get('products', 'ProductController::index');
        $client_routes->post('products/create', 'ProductController::create');
        $client_routes->post('products/update/(:alphanum)', 'ProductController::update/$1');
        $client_routes->get('products/show/(:alphanum)', 'ProductController::show/$1');
        $client_routes->post('products/updates', 'ProductController::updates');
        $client_routes->post('products/deletes', 'ProductController::multipleDeletes');
        $client_routes->get('products/views/result/(:alphanum)', 'ProductViewController::result/$1');
        $client_routes->presenter('products/views', ['controller' => 'ProductViewController']);

        /** Test Drive Module */
        $client_routes->get('test-drives', 'TestDriveController::index');
        $client_routes->post('test-drives/create', 'TestDriveController::create');
        $client_routes->get('test-drives/show/(:num)', 'TestDriveController::show/$1');
        $client_routes->post('test-drives/update/(:num)', 'TestDriveController::update/$1');
        $client_routes->post('test-drives/deletes', 'TestDriveController::multipleDeletes');
        $client_routes->post('test-drives/start/(:num)', 'TestDriveController::start/$1');
        $client_routes->post('test-drives/complete/(:num)', 'TestDriveController::complete/$1');
        $client_routes->get('test-drives/session/(:segment)', 'TestDriveController::getBySession/$1');
        $client_routes->get('test-drives/audit-trail/(:num)', 'TestDriveController::auditTrail/$1');
        $client_routes->get('test-drives/numberplates', 'TestDriveController::getNumberPlates');
        $client_routes->get('test-drives/reindex', 'TestDriveController::updateSearchIndex');
        $client_routes->get('test-drives/gpstats', 'TestDriveController::getGpsStats');
        $client_routes->get('test-drives/(:alphanum)/show/(:alphanum)', 'TestDriveController::getLeadTestdrive/$1/$2');

        $client_routes->get('test-drives/views/result/(:alphanum)', 'TestDriveViewController::result/$1');
        $client_routes->presenter('test-drives/views', ['controller' => 'TestDriveViewController']);


        /** Test Drive Customers */
        $client_routes->get('test-drives-customers', 'TestDriveCustomerController::index');
        $client_routes->post('test-drives-customers/create', 'TestDriveCustomerController::create');
        $client_routes->get('test-drives-customers/show/(:num)', 'TestDriveCustomerController::show/$1');
        $client_routes->post('test-drives-customers/update/(:num)', 'TestDriveCustomerController::update/$1');
        $client_routes->post('test-drives-customers/deletes', 'TestDriveCustomerController::multipleDeletes');
        $client_routes->get('test-drives-customers/views/result/(:alphanum)', 'TestDriveCustomerViewController::result/$1');
        $client_routes->presenter('test-drives-customers/views', ['controller' => 'TestDriveCustomerViewController']);

        /** Test Drive Media */
        $client_routes->post('test-drives/(:num)/photos/upload', 'TestDriveMediaController::uploadPhoto/$1');
        $client_routes->post('test-drives/(:num)/documents/upload', 'TestDriveMediaController::uploadDocument/$1');
        $client_routes->post('test-drives/(:num)/signature/upload', 'TestDriveMediaController::uploadSignature/$1');
        $client_routes->get('test-drives/(:num)/photos', 'TestDriveMediaController::getPhotos/$1');
        $client_routes->get('test-drives/(:num)/documents', 'TestDriveMediaController::getDocuments/$1');

        /** Test Drive GPS Tracking */
        $client_routes->post('test-drives/gps/ingest', 'TestDriveGpsPointController::ingest');
        $client_routes->post('test-drives/gps/ingest-single', 'TestDriveGpsPointController::ingestSingle');
        $client_routes->get('test-drives/(:num)/gps/route', 'TestDriveGpsPointController::getRoute/$1');
        $client_routes->get('test-drives/(:num)/gps/distance', 'TestDriveGpsPointController::getDistance/$1');

        /** Test Drive Export */
        $client_routes->get('test-drives/(:num)/export/pdf', 'TestDriveExportController::exportPDF/$1');
        $client_routes->get('test-drives/(:num)/export/csv', 'TestDriveExportController::exportCSV/$1');
        $client_routes->get('test-drives/(:num)/export/gps-route', 'TestDriveExportController::exportGPSRoute/$1');




        /** Calculators **/
        $client_routes->post('calculator/line-total', 'CalculatorController::calculateLineTotal');
        $client_routes->post('calculator/finance', 'CalculatorController::calculateFinancingAmount');

        /** Deal Module */
        $client_routes->get('deals', 'DealController::index');
        $client_routes->post('deals/create', 'DealController::create');
        $client_routes->post('deals/update/(:alphanum)', 'DealController::update/$1');
        $client_routes->get('deals/lock/(:alphanum)', 'DealController::lock/$1');
        $client_routes->post('deals/unlock/(:alphanum)', 'DealController::unlock/$1');
        $client_routes->get('deals/show/(:alphanum)', 'DealController::show/$1');
        $client_routes->get('deals/summary/show/(:alphanum)', 'DealController::summary/$1');
        $client_routes->get('deals/(:alphanum)/customers/show/(:alphanum)', 'DealController::getDealCustomer/$1/$2');
        $client_routes->post('deals/roll-a-deal', 'DealController::adjustDeal');
        $client_routes->get('deals/breakdown/(:alphanum)', 'DealController::completeDealCalculationBreakdown/$1');
        $client_routes->get('deals/sale-commissions/recalculate/(:alphanum)', 'DealController::recalculateCalculatedSalesCommission/$1');
        $client_routes->post('deals/attachments/zip/(:alphanum)', 'DealController::downloadAttachmentZip/$1');

        $client_routes->addPlaceholder('deal_pdf', '(?i)billofsale|test-drive|deposit-agreement|credit-application|trade-in|trade-in-disclosures|vehicle-history-report-acknowledgement|customer-worksheet|open-disclosure-agreement|vehicle-disclosures|deal-recap|alternate-billofsale|exempt-motor-vehicle|internal-inspection');
        $client_routes->get('deals/pdf/(:deal_pdf)/generate/(:alphanum)', 'DealController::generatePDF/$1/$2');
        $client_routes->get('deals/print/delivery-checklist/(:alphanum)', 'DealController::printDeliveryChecklist/$1');
        $client_routes->post('deals/deletes', 'DealController::multipleDeletes');
        $client_routes->post('deals/import', 'DealImportController::completeDealEntity');
        $client_routes->addPlaceholder('deal_import_parser_source', '(?i)dealertrack');
        $client_routes->post('deals/import/parser/pdf/(:deal_import_parser_source)', 'DealImportController::parsePDF/$1', ['filter' => 'protected-pdf-parser-throttle']);

        $client_routes->get('deals/reports', 'DealController::reportIndex');
        /** Deal Report View Routes */
        $client_routes->addPlaceholder('report_types', '(?i)pdf|xls|csv');
        $client_routes->post('deals/reports/(:report_types)/report', 'DealController::generateReport/$1');
        //$client_routes->presenter('vendors/views', ['controller' => 'VendorViewController']);

        $client_routes->addPlaceholder('deal_common', '(?i)fees|accessories|insurances|payments|warranties|guarantee|finance|dealbreakdown|delivery-checklist|sale-commissions|dealertrack');
        /** Deal Dependent Routes */
        $client_routes->post('deals/(:alphanum)/common/(:deal_common)/create', 'DealCommonDependentController::create/$1/$2');
        $client_routes->post('deals/(:alphanum)/common/(:deal_common)/update/(:alphanum)', 'DealCommonDependentController::update/$1/$2/$3');
        $client_routes->get('deals/(:alphanum)/common/(:deal_common)/show/(:alphanum)', 'DealCommonDependentController::show/$1/$2/$3');
        $client_routes->get('deals/(:alphanum)/common/(:deal_common)/delete/(:alphanum)', 'DealCommonDependentController::delete/$1/$2/$3');
        $client_routes->get('deals/(:alphanum)/common/(:deal_common)', 'DealCommonDependentController::index/$1/$2');
        $client_routes->post('deals/common/(:deal_common)/validate', 'DealCommonDependentController::onlyValidate/$1');

        /** Deal TradeIn Routes */
        $client_routes->post('deals/(:alphanum)/tradein/create', 'DealTradeInController::create/$1');
        $client_routes->post('deals/(:alphanum)/tradein/update/(:alphanum)', 'DealTradeInController::update/$1/$2');
        $client_routes->get('deals/(:alphanum)/tradein/show/(:alphanum)', 'DealTradeInController::show/$1/$2');
        $client_routes->get('deals/(:alphanum)/tradein/delete/(:alphanum)', 'DealTradeInController::delete/$1/$2');
        $client_routes->get('deals/(:alphanum)/tradein', 'DealTradeInController::index/$1');

        /** Deal Jacket Routes */
        $client_routes->get('deals/(:alphanum)/deal-jacket', 'DealJacketController::index/$1');
        $client_routes->post('deals/(:alphanum)/deal-jacket/documents/create', 'DealJacketController::create/$1');
        $client_routes->post('deals/(:alphanum)/deal-jacket/documents/partial/update/(:alphanum)', 'DealJacketController::partialUpdate/$1/$2');
        $client_routes->get('deals/(:alphanum)/deal-jacket/documents/show/(:alphanum)', 'DealJacketController::getDocumentWithEncryptedURL/$1/$2');
        $client_routes->post('deals/(:alphanum)/deal-jacket/documents/update/tagging/(:alphanum)', 'DealJacketController::updateTagging/$1/$2');
        $client_routes->get('deals/(:alphanum)/deal-jacket/documents/delete/(:alphanum)', 'DealJacketController::delete/$1/$2');
        $client_routes->get('deals/(:alphanum)/deal-jacket/template', 'DealJacketController::getTemplate/$1');
        $client_routes->post('deals/deal-jacket/template/validate', 'DealJacketController::validateTemplate');


        $client_routes->addPlaceholder('deal_trade_in_common', '(?i)disclosures');
        /** Deal Trade In Dependent Routes */
        $client_routes->post('deals/(:alphanum)/tradein/common/(:deal_trade_in_common)', 'DealTradeInCommonDependentController::bulkCreateOrUpdate/$1/$2');
        $client_routes->post('deals/(:alphanum)/tradein/common/(:deal_trade_in_common)/create', 'DealTradeInCommonDependentController::create/$1/$2');
        $client_routes->post('deals/(:alphanum)/tradein/common/(:deal_trade_in_common)/update/(:alphanum)', 'DealTradeInCommonDependentController::update/$1/$2/$3');
        $client_routes->get('deals/(:alphanum)/tradein/common/(:deal_trade_in_common)/show/(:alphanum)', 'DealTradeInCommonDependentController::show/$1/$2/$3');
        $client_routes->get('deals/(:alphanum)/tradein/common/(:deal_trade_in_common)/delete/(:alphanum)', 'DealTradeInCommonDependentController::delete/$1/$2/$3');
        $client_routes->get('deals/(:alphanum)/tradein/common/(:deal_trade_in_common)', 'DealTradeInCommonDependentController::index/$1/$2');
        $client_routes->get('deals/(:alphanum)/tradein/common/(:deal_trade_in_common)/delete', 'DealTradeInCommonDependentController::bulkDelete/$1/$2');

        /*** Deal Notes */
        $client_routes->post('deals/(:alphanum)/notes/create', 'DealController::noteCreate/$1');
        $client_routes->get('deals/(:alphanum)/notes', 'DealController::allNotes/$1');
        $client_routes->get('deals/(:alphanum)/notes/show/(:alphanum)', 'DealController::showNote/$1/$2');
        $client_routes->post('deals/(:alphanum)/notes/update/(:alphanum)', 'DealController::updateNote/$1/$2');
        $client_routes->get('deals/(:alphanum)/notes/delete/(:alphanum)', 'DealController::deleteNote/$1/$2');

        /*** DealerTrack */
        $client_routes->get('dealer-track/send/(:alphanum)', 'DealerTrackController::send/$1');
        $client_routes->post('dealer-track/configurations/save', 'DealerTrackController::configurationUpdate');

        /*** Activities Module */
        $client_routes->get('activities', 'ActivityController::index');
        $client_routes->post('activities/create', 'ActivityController::create');
        $client_routes->post('activities/update/(:alphanum)', 'ActivityController::update/$1');
        $client_routes->get('activities/show/(:alphanum)', 'ActivityController::show/$1');
        $client_routes->get('activities/delete/(:alphanum)', 'ActivityController::delete/$1');
        $client_routes->post('activities/deletes', 'ActivityController::multipleDeletes');
        $client_routes->post('activities/updates', 'ActivityController::updates');

        /** Activity View Routes */
        $client_routes->get('activities/views/result/(:alphanum)', 'ActivityViewController::result/$1');
        $client_routes->presenter('activities/views', ['controller' => 'ActivityViewController']);

        /** Deal View Routes */
        $client_routes->get('deals/views/result/(:alphanum)', 'DealViewController::result/$1', ['filter' => 'protected-api-throttle']);
        $client_routes->presenter('deals/views', ['controller' => 'DealViewController'], ['filter' => 'protected-api-throttle']);

        /** Deal Report View Routes */
        $client_routes->get('deal-reports/views/result/(:alphanum)', 'DealReportViewController::result/$1');
        $client_routes->get('deal-reports/views/(:report_types)/report/(:alphanum)', 'DealReportViewController::generateReport/$1/$2');
        $client_routes->presenter('deal-reports/views', ['controller' => 'DealReportViewController']);

        /** Lead Report View Routes */
        $client_routes->addPlaceholder('report_types', '(?i)pdf|xls|csv');
        $client_routes->get('lead-reports/views/result/(:alphanum)', 'LeadReportViewController::result/$1');
        $client_routes->get('lead-reports/views/(:report_types)/report/(:alphanum)', 'LeadReportViewController::generateReport/$1/$2');
        $client_routes->presenter('lead-reports/views', ['controller' => 'LeadReportViewController']);
        $client_routes->get('lead-reports/report', 'LeadResponseController::reportIndex');
        $client_routes->post('lead-reports/report/(:report_types)', 'LeadResponseController::generateReport/$1');

        /** Inventory View Routes */
        $client_routes->get('inventory-reports/views/result/(:alphanum)', 'InventoryReportViewController::result/$1');
        $client_routes->get('inventory-reports/views/(:report_types)/report/(:alphanum)', 'InventoryReportViewController::generateReport/$1/$2');
        $client_routes->presenter('inventory-reports/views', ['controller' => 'InventoryReportViewController']);

        $client_routes->get('inventory/reports', 'InventoryReportController::reportIndex');
        /** Deal Report View Routes */
        $client_routes->addPlaceholder('report_types', '(?i)pdf|xls|csv');
        $client_routes->post('inventory/reports/(:report_types)/report', 'InventoryReportController::generateReport/$1');


        /*** Facebook Marketplace View Routes */
        $client_routes->get('facebook-marketplace', 'FacebookMarketPlaceController::index');
        $client_routes->post('facebook-marketplace/deletes', 'FacebookMarketPlaceController::multipleDeletes');
        $client_routes->get('facebook-marketplace/status/updated', 'FacebookMarketPlaceController::changeStatusFromToBeUpdatedToUpdated');
        $client_routes->get('facebook-marketplace/update-default-categories', 'FacebookMarketPlaceController::updateDefaultFacebookCategories');
        $client_routes->post('facebook-marketplace/purge', 'FacebookMarketPlaceController::filterDataAndPurgeDelete');
        $client_routes->presenter('facebook-marketplace/views', ['controller' => 'FacebookMarketPlaceViewController']);

        /*** Craigslist View Routes */
        $client_routes->get('craigslist', 'CraigslistController::index', ['filter' => 'auth-elevated-access']);
        $client_routes->post('craigslist/purge', 'CraigslistController::filterDataAndPurgeDelete', ['filter' => 'auth-elevated-access']);
        $client_routes->presenter('craigslist/views', ['controller' => 'CraigslistControllerViewController']);


        /** Cost Setting Module */
        $client_routes->addPlaceholder('cost_placeholders', '(?i)costs|fees|warranty|guarantee|accessories|insurances');
        $client_routes->get('setting/(:cost_placeholders)', 'CostSettingController::index/$1');
        $client_routes->post('setting/(:cost_placeholders)/create', 'CostSettingController::create/$1');
        $client_routes->post('setting/(:cost_placeholders)/update/(:alphanum)', 'CostSettingController::update/$1/$2');
        $client_routes->get('setting/(:cost_placeholders)/show/(:alphanum)', 'CostSettingController::show/$1/$2');
        $client_routes->get('setting/(:cost_placeholders)/delete/(:alphanum)', 'CostSettingController::delete/$1/$2');


        /** Syndication  */
        $client_routes->add('syndication', 'SyndicationController::index');
        $client_routes->get('syndication/healthcheck', 'SyndicationController::healthcheck');

        /** SocialConnect  */
        $client_routes->get('socialconnect/dashboard', 'SocialController::getSocialConnect');
        // @todo : change them to use login and revoke function.
        $client_routes->get('socialconnect/getFacebookUrl', 'SocialController::getFacebookUrl');
        $client_routes->get('socialconnect/getInstagramUrl', 'SocialController::getInstagramUrl');
        $client_routes->get('socialconnect/unlinkFacebook', 'SocialController::getFacebookUnlinked');
        $client_routes->get('socialconnect/unlinkInstagram', 'SocialController::getInstagramUnlinked');
        $client_routes->get('socialconnect/postToSocial/(:num)/(:alphanum)', 'SocialController::postToSocial/$1/$2');
        $client_routes->presenter('facebook-social/views', ['controller' => 'SocialConnectViewController']);

        $client_routes->addPlaceholder('socialconnect_source', '(?i)google');
        $client_routes->get('socialconnect/login/(:socialconnect_source)', 'SocialController::OauthAuthenticateUrl/$1');
        $client_routes->get('socialconnect/revoke/(:socialconnect_source)', 'SocialController::revoke/$1');

        /** Payment  **/
        $client_routes->addPlaceholder('payment_placeholders', '(?i)lead|deal');
        $client_routes->get('payments', 'PaymentController::index');
        $client_routes->post('payments/create', 'PaymentController::create');
        $client_routes->post('payments/online/create', 'StripePaymentController::create');
        $client_routes->post('payments/online/refund', 'StripePaymentController::refundPayment');
        $client_routes->get('payments/(:payment_placeholders)/show/(:alphanum)', 'PaymentController::getModulePayment/$1/$2');
        $client_routes->get('payments/pdf/receipt/generate/(:alphanum)', 'PaymentController::generatePDF/$1');
        $client_routes->get('payments/delete/(:alphanum)', 'PaymentController::delete/$1');

        $client_routes->get('payments/reports', 'PaymentController::reportIndex');
        $client_routes->post('payments/reports/(:report_types)/report', 'PaymentController::generateReport/$1');

        /** Payment Views **/
        $client_routes->get('payments/views/result/(:alphanum)', 'PaymentViewController::result/$1');
        $client_routes->presenter('payments/views', ['controller' => 'PaymentViewController']);

        /** WorkOrder */
        $client_routes->get('work-orders', 'WorkOrderController::index');
        $client_routes->get('work-orders/show/(:alphanum)', 'WorkOrderController::show/$1');
        $client_routes->post('work-orders/create', 'WorkOrderController::create');
        $client_routes->post('work-orders/update/(:alphanum)', 'WorkOrderController::update/$1');
        $client_routes->post('work-orders/deletes', 'WorkOrderController::multipleDeletes', ['filter' => 'auth-elevated-access']);
        $client_routes->post('work-orders/updates', 'WorkOrderController::updates');

        $client_routes->addPlaceholder('work_order_pdfs', '(?i)work-order-complete|work-order-summary');
        $client_routes->get('work-orders/pdf/(:work_order_pdfs)/generate/(:alphanum)', 'WorkOrderController::generatePDF/$1/$2');

        $client_routes->get('work-orders/views/result/(:alphanum)', 'WorkOrderViewController::result/$1', ['filter' => 'auth-elevated-access']);
        $client_routes->presenter('work-orders/views', ['controller' => 'WorkOrderViewController']);

        /** WorkJob */
        $client_routes->get('work-orders/(:alphanum)/work-jobs', 'WorkJobController::index/$1');
        $client_routes->get('work-orders/(:alphanum)/work-jobs/show/(:alphanum)', 'WorkJobController::show/$1/$2');
        $client_routes->post('work-orders/(:alphanum)/work-jobs/create', 'WorkJobController::create/$1');
        $client_routes->post('work-orders/(:alphanum)/work-jobs/update/(:alphanum)', 'WorkJobController::update/$1/$2');
        $client_routes->get('work-orders/(:alphanum)/work-jobs/delete/(:alphanum)', 'WorkJobController::delete/$1/$2');

        /** WorkJob Dependents */
        $client_routes->addPlaceholder('work_job_common', '(?i)products');
        $client_routes->post('work-jobs/(:alphanum)/common/(:work_job_common)/create', 'WorkJobCommonDependentController::create/$1/$2');
        $client_routes->post('work-jobs/(:alphanum)/common/(:work_job_common)/update/(:alphanum)', 'WorkJobCommonDependentController::update/$1/$2/$3');
        $client_routes->get('work-jobs/(:alphanum)/common/(:work_job_common)/show/(:alphanum)', 'WorkJobCommonDependentController::show/$1/$2/$3');
        $client_routes->get('work-jobs/(:alphanum)/common/(:work_job_common)/delete/(:alphanum)', 'WorkJobCommonDependentController::delete/$1/$2/$3');
        $client_routes->get('work-jobs/(:alphanum)/common/(:work_job_common)', 'WorkJobCommonDependentController::index/$1/$2');


        /** Invoice  */
        $client_routes->get('invoice', 'InvoiceController::getInvoiceList');
        $client_routes->get('invoice/update', 'InvoiceController::updateInvoiceList');
        $client_routes->get('invoice/download/(:num)', 'InvoiceController::downloadInvoice/$1');
        $client_routes->get('subscription', 'InvoiceController::getSubscription');
        $client_routes->get('subscription/update', 'InvoiceController::updateSubscription');
        $client_routes->get('invoice/overdue', 'InvoiceController::getPendingInvoiceList');

        /** Conversation */
        $client_routes->post('conversation/email/lead', 'EmailConversationController::getConversation');
        $client_routes->post('conversation/email/lead/show/(:alphanum)', 'EmailConversationController::getEmailDetails/$1');
        $client_routes->post('conversation/email/lead/attachment/(:alphanum)', 'EmailConversationController::downloadEmailAttachment/$1');
        $client_routes->post('conversation/email/lead/compose', 'EmailConversationController::composeEmail');

        /** SMS Conversation */

        $client_routes->post('conversation/sms', 'SMSConversationController::getConversation');
        $client_routes->get('conversation/sms/all', 'SMSConversationController::index');
        $client_routes->get('conversation/sms/stats', 'SMSConversationController::userSMSStats');
        $client_routes->post('conversation/sms/compose', 'SMSConversationController::compose');
        $client_routes->post('conversation/sms/status/read', 'SMSConversationController::updateRead');

        $client_routes->post('user/integrations/email', 'UserEmailConfigurationController::create');
        $client_routes->get('user/integrations/email', 'UserEmailConfigurationController::show');
        $client_routes->get('user/integrations/email/delete/(:alphanum)', 'UserEmailConfigurationController::delete/$1');

        /** User SMS Configuration */
        $client_routes->get('user/integrations/sms', 'UserSMSConfigurationController::index', ['filter' => 'auth-elevated-access']);
        $client_routes->post('user/integrations/sms/create', 'UserSMSConfigurationController::create', ['filter' => 'auth-elevated-access']);
        $client_routes->post('user/integrations/sms/update/(:alphanum)', 'UserSMSConfigurationController::update/$1', ['filter' => 'auth-elevated-access']);
        $client_routes->get('user/integrations/sms/show/(:alphanum)', 'UserSMSConfigurationController::show/$1', ['filter' => 'auth-elevated-access']);

        /** RealTime Communications */
        $client_routes->post('real-time/notification/emails', 'ClientRealTimeNotificationController::sendEmail');

        $client_routes->get('model-deals', 'DealReplicatorController::index');
        $client_routes->post('model-deals/create', 'DealReplicatorController::create');
        $client_routes->post('model-deals/update/(:alphanum)', 'DealReplicatorController::update/$1');
        $client_routes->get('model-deals/show/(:alphanum)', 'DealReplicatorController::show/$1');

        $client_routes->addPlaceholder('deal_replicator_common', '(?i)fees|accessories|insurances|payments|warranties|guarantee');
        /** Deal Replicator Dependent Routes */
        $client_routes->post('model-deals/(:alphanum)/common/(:deal_replicator_common)/create', 'DealReplicatorCommonDependentController::create/$1/$2');
        $client_routes->post('model-deals/(:alphanum)/common/(:deal_replicator_common)/update/(:alphanum)', 'DealReplicatorCommonDependentController::update/$1/$2/$3');
        $client_routes->get('model-deals/(:alphanum)/common/(:deal_replicator_common)/show/(:alphanum)', 'DealReplicatorCommonDependentController::show/$1/$2/$3');
        $client_routes->get('model-deals/(:alphanum)/common/(:deal_replicator_common)/delete/(:alphanum)', 'DealReplicatorCommonDependentController::delete/$1/$2/$3');
        $client_routes->get('model-deals/(:alphanum)/common/(:deal_replicator_common)', 'DealReplicatorCommonDependentController::index/$1/$2');

        /** Review Module */
        $client_routes->get('reviews', 'ReviewController::index', ['filter' => 'auth-elevated-access']);
        $client_routes->post('reviews/create', 'ReviewController::create', ['filter' => 'auth-elevated-access']);
        $client_routes->post('reviews/update/(:alphanum)', 'ReviewController::update/$1', ['filter' => 'auth-elevated-access']);
        $client_routes->get('reviews/show/(:alphanum)', 'ReviewController::show/$1', ['filter' => 'auth-elevated-access']);
        $client_routes->post('reviews/updates', 'ReviewController::updates', ['filter' => 'auth-elevated-access']);
        $client_routes->post('reviews/deletes', 'ReviewController::multipleDeletes', ['filter' => 'auth-elevated-access']);
        $client_routes->get('reviews/views/result/(:alphanum)', 'ReviewViewController::result/$1');
        $client_routes->presenter('reviews/views', ['controller' => 'ReviewViewController']);

        /** External Reviews */
        $client_routes->addPlaceholder('external_review_source', '(?i)google|facebook');
        $client_routes->get('external-reviews/login/(:external_review_source)', 'ExternalReviewController::OauthAuthenticateUrl/$1');
        $client_routes->get('external-reviews/fetch/(:external_review_source)', 'ExternalReviewController::fetchReviews/$1');
        $client_routes->get('external-reviews/revoke/(:external_review_source)', 'ExternalReviewController::revoke/$1');

        /** Feedback Module */
        $client_routes->get('feedbacks', 'ReviewFeedbackController::index');
        $client_routes->post('feedbacks/create/(:alphanum)', 'ReviewFeedbackController::create/$1');
        $client_routes->post('feedbacks/updates', 'ReviewFeedbackController::updates');

        /** Review Feedback Views **/
        $client_routes->get('feedbacks/views/result/(:alphanum)', 'ReviewFeedbackViewController::result/$1');
        $client_routes->presenter('feedbacks/views', ['controller' => 'ReviewFeedbackViewController']);

        /** WorkFlow Template Module */
        $client_routes->get('workflow-templates', 'WorkFlowTemplateController::index', ['filter' => 'auth-elevated-access']);
        $client_routes->get('workflow-templates/show/(:alphanum)', 'WorkFlowTemplateController::show/$1', ['filter' => 'auth-elevated-access']);
        $client_routes->post('workflow-templates/create', 'WorkFlowTemplateController::create', ['filter' => 'auth-elevated-access']);
        $client_routes->get('workflow-templates/delete/(:alphanum)', 'WorkFlowTemplateController::delete/$1', ['filter' => 'auth-elevated-access']);

        /** Attachment Module */
        $client_routes->post('attachments/create', 'AttachmentController::create');
        $client_routes->post('attachments/delete', 'AttachmentController::deleteByUrl');

        /** WorkFlow Template Dependents */
        $client_routes->addPlaceholder('workflow_template_common', '(?i)steps');
        $client_routes->post('workflow-templates/(:alphanum)/common/(:workflow_template_common)/create', 'WorkFlowTemplateCommonDependentController::create/$1/$2', ['filter' => 'auth-elevated-access']);
        $client_routes->post('workflow-templates/(:alphanum)/common/(:workflow_template_common)/update/(:alphanum)', 'WorkFlowTemplateCommonDependentController::update/$1/$2/$3', ['filter' => 'auth-elevated-access']);
        $client_routes->get('workflow-templates/(:alphanum)/common/(:workflow_template_common)/show/(:alphanum)', 'WorkFlowTemplateCommonDependentController::show/$1/$2/$3', ['filter' => 'auth-elevated-access']);
        $client_routes->get('workflow-templates/(:alphanum)/common/(:workflow_template_common)/delete/(:alphanum)', 'WorkFlowTemplateCommonDependentController::delete/$1/$2/$3', ['filter' => 'auth-elevated-access']);
        $client_routes->get('workflow-templates/(:alphanum)/common/(:workflow_template_common)', 'WorkFlowTemplateCommonDependentController::index/$1/$2');
        $client_routes->post('workflow-templates/(:alphanum)/common/steps/sort', 'WorkFlowTemplateCommonDependentController::sortIndex/$1');

        /** Regional Seed */
        $client_routes->addPlaceholder('region', '(?i)OntarioUCDA|OntarioGeneric|AlbertaGeneric|BritishColumbiaGeneric');
        $client_routes->post('regions/(:region)/seed', 'RegionalSeedController::seed/$1');

        $client_routes->group('import', function ($client_routes) {
            $client_routes->post('inventory', 'InventoryImportController::importInventory');
            $client_routes->addPlaceholder('third_party_inventory_source', '(?i)openlane|otolane');
            $client_routes->addPlaceholder('third_party_inventory_api_source', '(?i)pbs');
            $client_routes->post('inventory/third-party/(:third_party_inventory_source)', 'InventoryImportController::thirdPartyInventoryImport/$1');
            $client_routes->get('inventory/third-party-api/(:third_party_inventory_api_source)', 'InventoryImportController::thirdPartyAPIBasedInventoryImport/$1');
        });

        $client_routes->get('campaigns', 'AdsController::getCampaigns');
        $client_routes->get('get-campaigns', 'AdsController::getCampaignsAndSave');
        $client_routes->get('campaigns/allstats', 'AdsController::getAllCampaignStats');

        /** Schedule Template Module */
        $client_routes->get('schedule-templates', 'ScheduleTemplateController::index', ['filter' => 'auth-elevated-access']);
        $client_routes->get('schedule-templates/show/(:alphanum)', 'ScheduleTemplateController::show/$1', ['filter' => 'auth-elevated-access']);
        $client_routes->post('schedule-templates/create', 'ScheduleTemplateController::create', ['filter' => 'auth-elevated-access']);
        $client_routes->post('schedule-templates/update/(:alphanum)', 'ScheduleTemplateController::update/$1', ['filter' => 'auth-elevated-access']);
        $client_routes->get('schedule-templates/delete/(:alphanum)', 'ScheduleTemplateController::delete/$1', ['filter' => 'auth-elevated-access']);

        /** Digital Retailing Module */
        $client_routes->get('digital-retailing/settings/finance', 'DigitalRetailSettingController::showFinanceTerms');
        $client_routes->post('digital-retailing/settings/finance', 'DigitalRetailSettingController::updateFinanceTerms');

        /** Payment Report View Routes */
        $client_routes->get('payment-reports/views/result/(:alphanum)', 'PaymentReportViewController::result/$1');
        $client_routes->get('payment-reports/views/(:report_types)/report/(:alphanum)', 'PaymentReportViewController::generateReport/$1/$2');
        $client_routes->presenter('payment-reports/views', ['controller' => 'PaymentReportViewController']);

        /** Cost Variable Matrix Module */
        $client_routes->get('cost-variable-matrix', 'CostVariationMatrixController::index');
        $client_routes->post('cost-variable-matrix/create', 'CostVariationMatrixController::create', ['filter' => 'auth-elevated-access']);
        $client_routes->get('cost-variable-matrix/show/(:alphanum)', 'CostVariationMatrixController::show/$1');
        $client_routes->get('cost-variable-matrix/delete/(:alphanum)', 'CostVariationMatrixController::delete/$1', ['filter' => 'auth-elevated-access']);

        /** Inventory Cost Report View Routes */
        $client_routes->get('inventory-cost-reports/views/result/(:alphanum)', 'InventoryCostReportViewController::result/$1');
        $client_routes->get('inventory-cost-reports/views/(:report_types)/report/(:alphanum)', 'InventoryCostReportViewController::generateReport/$1/$2');
        $client_routes->presenter('inventory-cost-reports/views', ['controller' => 'InventoryCostReportViewController']);

        /** Inventory Cost Report View Routes */
        $client_routes->get('inventory-purchase-reports/views/result/(:alphanum)', 'InventoryPurchaseReportViewController::result/$1');
        $client_routes->get('inventory-purchase-reports/views/(:report_types)/report/(:alphanum)', 'InventoryPurchaseReportViewController::generateReport/$1/$2');
        $client_routes->presenter('inventory-purchase-reports/views', ['controller' => 'InventoryPurchaseReportViewController']);

        /** Voucher Template Module */
        $client_routes->get('voucher-templates', 'VoucherTemplateController::index');
        $client_routes->get('voucher-templates/show/(:alphanum)', 'VoucherTemplateController::show/$1', ['filter' => 'auth-elevated-access']);
        $client_routes->post('voucher-templates/create', 'VoucherTemplateController::create', ['filter' => 'auth-elevated-access']);
        $client_routes->post('voucher-templates/update/(:alphanum)', 'VoucherTemplateController::update/$1', ['filter' => 'auth-elevated-access']);
        $client_routes->get('voucher-templates/delete/(:alphanum)', 'VoucherTemplateController::delete/$1', ['filter' => 'auth-elevated-access']);

        /** Voucher Module */
        $client_routes->get('vouchers', 'VoucherController::index');
        $client_routes->get('vouchers/show/(:alphanum)', 'VoucherController::show/$1');
        $client_routes->post('vouchers/create', 'VoucherController::create');
        $client_routes->post('vouchers/update/(:alphanum)', 'VoucherController::update/$1');
        $client_routes->get('vouchers/delete/(:alphanum)', 'VoucherController::delete/$1');
        $client_routes->post('vouchers/clone', 'VoucherController::clone');
        $client_routes->get('vouchers/coupon/(:alphanum)', 'VoucherController::downloadCoupon/$1');
        $client_routes->post('vouchers/deletes', 'VoucherController::multipleDeletes', ['filter' => 'auth-elevated-access']);
        $client_routes->post('vouchers/updates', 'VoucherController::updates');

        /** Voucher Views **/
        $client_routes->get('vouchers/views/result/(:alphanum)', 'VoucherViewController::result/$1', ['filter' => 'auth-elevated-access']);
        $client_routes->presenter('vouchers/views', ['controller' => 'VoucherViewController']);

        /** Customer Report Routes */
        $client_routes->get('customer-reports/views/result/(:alphanum)', 'CustomerReportViewController::result/$1', ['filter' => 'auth-elevated-access']);
        $client_routes->get('customer-reports/views/(:report_types)/report/(:alphanum)', 'CustomerReportViewController::generateReport/$1/$2', ['filter' => 'auth-elevated-access']);
        $client_routes->presenter('customer-reports/views', ['controller' => 'CustomerReportViewController', 'filter' => 'auth-elevated-access']);

        /** Carfax Report Routes */
        $client_routes->post('carfax/reports/lookup', 'CarfaxController::lookUp');
        $client_routes->post('carfax/reports/order', 'CarfaxController::orderReport');
        $client_routes->get('carfax/reports/logs', 'CarfaxController::getLogs');
        $client_routes->get('carfax/reports/available', 'CarfaxController::isCarfaxAPIAvailable');

        /** Valuation Routes */
        $client_routes->post('carfax/valuations/lookup/vin', 'ValuationController::lookupVehicleByVin', ['filter' => 'restricted-auth-service:carfax_market_valuation_api']);
        $client_routes->post('carfax/valuations/lookup/vin/trim', 'ValuationController::lookupVehicleByTrimAndVIN', ['filter' => 'restricted-auth-service:carfax_market_valuation_api']);
        $client_routes->post('carfax/valuations/lookup/valuation', 'ValuationController::valuation', ['filter' => 'restricted-auth-service:carfax_market_valuation_api,throttle.tenant.carfax_market_valuation.100.monthly']);
        $client_routes->post('carfax/valuations/lookup/year-make-model', 'ValuationController::lookUpVehicleByYearMakeModel', ['filter' => 'restricted-auth-service:carfax_market_valuation_api']);
        $client_routes->post('carfax/valuations/lookup/year-make-model/trim', 'ValuationController::lookUpVehicleByYearMakeModelAndTrim', ['filter' => 'restricted-auth-service:carfax_market_valuation_api']);


        /** DVI Template Routes */
        $client_routes->get('dvi-templates', 'DigitalVehicleInspectionTemplateController::index');
        $client_routes->get('dvi-templates/show/(:alphanum)', 'DigitalVehicleInspectionTemplateController::show/$1');
        $client_routes->post('dvi-templates/create', 'DigitalVehicleInspectionTemplateController::create');
        $client_routes->post('dvi-templates/update/(:alphanum)', 'DigitalVehicleInspectionTemplateController::update/$1');
        $client_routes->get('dvi-templates/delete/(:alphanum)', 'DigitalVehicleInspectionTemplateController::delete/$1');

        /** DVI Item Template Routes */
        $client_routes->get('dvi-template-items', 'DigitalVehicleInspectionTemplateItemController::index', ['filter' => 'auth-elevated-access']);
        $client_routes->get('dvi-template-items/show/(:alphanum)', 'DigitalVehicleInspectionTemplateItemController::show/$1', ['filter' => 'auth-elevated-access']);
        $client_routes->post('dvi-template-items/create', 'DigitalVehicleInspectionTemplateItemController::create', ['filter' => 'auth-elevated-access']);
        $client_routes->post('dvi-template-items/update/(:alphanum)', 'DigitalVehicleInspectionTemplateItemController::update/$1', ['filter' => 'auth-elevated-access']);
        $client_routes->get('dvi-template-items/delete/(:alphanum)', 'DigitalVehicleInspectionTemplateItemController::delete/$1', ['filter' => 'auth-elevated-access']);
        $client_routes->post('dvi-template-items/deletes', 'DigitalVehicleInspectionTemplateItemController::multipleDeletes', ['filter' => 'auth-elevated-access']);

        /** DVI Routes */
        $client_routes->get('dvi', 'DigitalVehicleInspectionController::index');
        $client_routes->post('dvi/clone', 'DigitalVehicleInspectionController::clone');
        $client_routes->get('dvi/render/(:alphanum)', 'DigitalVehicleInspectionController::render/$1');
        $client_routes->post('dvi/partial/update', 'DigitalVehicleInspectionController::partialUpdate');
        $client_routes->post('dvi/(:alphanum)/item/update/(:alphanum)', 'DigitalVehicleInspectionController::updateItem/$1/$2');
        $client_routes->get('dvi/(:alphanum)/item/show/(:alphanum)', 'DigitalVehicleInspectionController::showItem/$1/$2');
        $client_routes->get('dvi/pdf/dvi-complete/generate/(:alphanum)', 'DigitalVehicleInspectionController::generatePDF/$1');

        $client_routes->post('dvi/(:alphanum)/item/(:alphanum)/attachments/create', 'DigitalVehicleInspectionController::saveItemAttachment/$1/$2');
        $client_routes->get('dvi/(:alphanum)/item/(:alphanum)/attachments/delete/(:alphanum)', 'DigitalVehicleInspectionController::deleteItemAttachment/$1/$2/$3');

        $client_routes->get('dvi/views/result/(:alphanum)', 'DigitalVehicleInspectionViewController::result/$1');
        $client_routes->presenter('dvi/views', ['controller' => 'DigitalVehicleInspectionViewController']);
    });

    /*** APIs for the website */
    $client_routes->group('website', ['filter' => 'active-tenant-account'], function ($client_routes) {
        $client_routes->add('inventory', 'WebsiteController::inventory', ['filter' => 'public-api-throttle']);
        $client_routes->add('inventory/all', 'WebsiteController::inventory', ['filter' => 'public-api-throttle']);
        $client_routes->get('inventory/show/(:alphanum)', 'WebsiteController::inventory_id/$1');
        $client_routes->get('inventory/sticker/(:alphanum)', 'InventoryController::sticker/$1', ['filter' => 'cors']);
        $client_routes->get('inventory/sticker/v2/(:alphanum)', 'InventoryController::stickerV2/$1', ['filter' => 'cors']);
        $client_routes->get('inventory/filters', 'WebsiteController::filters', ['filter' => 'cors']);
        $client_routes->get('image/placeholder', 'ImageController::getPlaceholder', ['filter' => 'public-api-throttle']);
        $client_routes->get('inventory/images/(:alphanum)', 'WebsiteController::inventory_images/$1');

        $client_routes->get('inventory/vehicle-inspection/complete/(:any)', 'InventoryWebInspectionController::render/$1', ['filter' => 'cors']);
        $client_routes->get('inventory/vehicle-inspection/summary/(:any)', 'InventoryWebInspectionController::summary/$1', ['filter' => 'cors']);

        $client_routes->addPlaceholder('private_deal_pdf', '(?i)private-billofsale|private-test-drive|private-deposit-agreement|private-credit-application|private-trade-in|private-trade-in-disclosures|private-vehicle-history-report-acknowledgement|private-customer-worksheet|private-open-disclosure-agreement|private-alternate-billofsale|private-exempt-motor-vehicle');
        $client_routes->get('private/pdf/(:private_deal_pdf)/view/(:alphanum)', 'DealController::generatePDF/$1/$2');
        $client_routes->get('sitemap', 'WebsiteController::siteMap');
        $client_routes->post('robot/feed/fbmp', 'InventoryReportController::generateFBMPFeed');
        $client_routes->post('robot/feed/fbmp/show/(:alphanum)', 'FacebookMarketPlaceController::show/$1');
        $client_routes->post('robot/feed/fbmp/delete/(:alphanum)', 'FacebookMarketPlaceController::delete/$1');
        $client_routes->post('robot/feed/fbmp/update', 'FacebookMarketPlaceController::createOrUpdate');
        $client_routes->post('robot/feed/fbmp/credentials', 'FacebookMarketPlaceController::getCredentials');
        $client_routes->post('robot/feed/fbmp/notifications/send', 'FacebookMarketPlaceController::sendNotification');

        $client_routes->post('robot/feed/craigslist/show/(:alphanum)', 'CraigslistController::show/$1');
        $client_routes->post('robot/feed/craigslist/delete/(:alphanum)', 'CraigslistController::delete/$1');
        $client_routes->post('robot/feed/craigslist/update', 'CraigslistController::createOrUpdate');
        $client_routes->post('robot/feed/craigslist/location/(:alphanum)', 'CraigslistController::generateCraigslistFeed/$1');


        /*** Craigslist View Routes */
        $client_routes->get('craigslist', 'CraigslistController::index', ['filter' => 'auth-elevated-access']);

        /*** Locations Routes */
        $client_routes->post('locations/suggestion', 'ServiceController::getLocationSuggestions', ['filter' => 'protected-places-api-ip-throttle']);
        $client_routes->get('locations/(:any)', 'ServiceController::getALocation/$1', ['filter' => 'protected-places-api-ip-throttle']);

        /*** Reviews Routes */
        $client_routes->get('reviews/filters', 'ReviewViewController::filters');
        $client_routes->get('reviews/render', 'ReviewViewController::render');
        $client_routes->get('reviews/feedback/render/(:alphanum)', 'ReviewFeedbackController::render/$1');
        $client_routes->get('reviews/feedback/widget/render', 'ReviewFeedbackController::renderToUnregisteredCustomer');
        $client_routes->post('reviews/feedback/customer/create', 'ReviewFeedbackController::createWithOutCustomer');

        $client_routes->addPlaceholder('review_feedback_status', '(?i)positive|negative');
        $client_routes->get('reviews/feedback/(:review_feedback_status)/status/(:alphanum)', 'ReviewFeedbackController::updateStatus/$1/$2');
        $client_routes->post('reviews/feedback/update/(:alphanum)', 'ReviewFeedbackController::updateFeedback/$1');

        $client_routes->get('vouchers/coupon/status/(:alphanum)', 'VoucherController::checkCouponStatus/$1');
        $client_routes->options('(:any)', 'UpdateController::options/$1');

        $client_routes->add('configuration', 'WebsiteController::getWebsiteConfiguration', ['filter' => 'cors']);
    });

    /*** Version 2 APIs for the website */
    $client_routes->group('v2/website', ['filter' => 'public-api-throttle'], function ($client_routes) {
        $client_routes->get('inventory/show/(:alphanum)', 'WebsiteController::showCompleteInventory/$1');
        $client_routes->get('inventory/makes', 'WebsiteController::getVehicleMakes');
    });

    $client_routes->group('leads/responses', function ($client_routes) {
        $client_routes->add('create', 'LeadResponseController::create');
        $client_routes->add('getlead', 'LeadResponseController::send');
        $client_routes->get('show/(:alphanum)', 'LeadResponseController::show/$1', ['filter' => 'auth']);
        $client_routes->post('updates', 'LeadResponseController::updates', ['filter' => 'auth-elevated-access']);
        $client_routes->post('deletes', 'LeadResponseController::multipleDeletes', ['filter' => 'auth']);
    });

    /** Payment Routes */
    $client_routes->group('payments/online', function ($client_routes) {
        $client_routes->get('show/(:alphanum)', 'StripePaymentController::showPaymentPage/$1');
        $client_routes->post('capture', 'StripePaymentController::capturePayment');
    });

    $client_routes->options('business/seeds', 'UpdateController::options/$1');
    $client_routes->options('business/update', 'UpdateController::options/$1');
    $client_routes->options('business/inventory/search-index/update', 'UpdateController::options/$1');

    $client_routes->group('conversation/sms', function ($client_routes) {
        $client_routes->post('update', 'SMSConversationController::updateBySID');
        $client_routes->post('reply', 'SMSConversationController::customerReply');
    });

    $client_routes->addPlaceholder('external_review_source', '(?i)google|facebook');
    $client_routes->group('external-reviews', function ($client_routes) {
        $client_routes->add('callback/(:external_review_source)', 'ExternalReviewController::authorize/$1');
        $client_routes->get('review-publish-url/(:external_review_source)', 'ExternalReviewController::getReviewURI/$1');
    });

    $client_routes->addPlaceholder('socialconnect_source', '(?i)google');
    $client_routes->group('socialconnect', function ($client_routes) {
        $client_routes->add('callback/(:socialconnect_source)', 'SocialController::authorize/$1');
    });

    $client_routes->addPlaceholder('internal_region', '(?i)ontario|alberta|bc');
    $client_routes->addPlaceholder('internal_third_party_service', '(?i)dealer-rater');

    $client_routes->group('services', function ($client_routes) {
        $client_routes->post('one-time-password/request', 'ServiceController::generateOTP', ['filter' => 'otp-throttle']);
        $client_routes->post('roll-a-deal', 'DealController::adjustDeal', ['filter' => 'cors']);
        $client_routes->get('captcha/request', 'ServiceController::generateCaptcha');
        $client_routes->post('captcha/solver/send', 'CaptchaSolverController::sendCaptcha', ['filter' => 'captcha-solver-throttle']);
        $client_routes->post('captcha/solver/result', 'CaptchaSolverController::getResult');
        $client_routes->post('ocr/document-identification-parser', 'ServiceController::parseIdentificationImage', ['filter' => 'protected_ocr_parser_throttle']);
        $client_routes->get('ocr/document-identification-parser/(:any)', 'ServiceController::getParsedIdentificationImageResult/$1', ['filter' => 'protected_ocr_parser_throttle']);
        $client_routes->get('internal/region/zipcode/(:internal_region)', 'ServiceController::internalCrawlerZipCode/$1');
        $client_routes->get('internal/region/city/(:internal_region)', 'ServiceController::internalCrawlerCity/$1');
        $client_routes->get('internal/third-party-service/(:internal_third_party_service)', 'ServiceController::internalCrawlerThirdParty/$1');
        $client_routes->post('calculator/finance', 'CalculatorController::calculateFinancingAmount');
        $client_routes->post('calculator/build-worksheet', 'CalculatorController::buildWorksheet');
        $client_routes->post('locations/suggestion', 'ServiceController::getLocationSuggestions', ['filter' => 'protected-places-api-throttle']);
        $client_routes->get('locations/(:any)', 'ServiceController::getALocation/$1', ['filter' => 'protected-places-api-throttle']);
        $client_routes->add('vehicles/(:alphanum)', 'InventoryController::getBasicVehicleInformation/$1', ['filter' => 'public-vin-decoder-throttle']);
        $client_routes->options('(:any)', 'UpdateController::options/$1');
    });

    $client_routes->group('digital-retailing', function ($client_routes) {
        $client_routes->get('template/show/(:any)', 'DigitalRetailingController::template/$1', ['filter' => 'cors']);
        $client_routes->post('transient/save', 'DigitalRetailingController::saveTransient', ['filter' => 'cors']);
        $client_routes->post('transient/get', 'DigitalRetailingController::getTransient', ['filter' => 'cors']);
        $client_routes->post('quote/save', 'DigitalRetailingController::createQuote', ['filter' => 'cors']);
        $client_routes->get('credit-application/render', 'DigitalRetailingController::renderCreditApplicationTemplate', ['filter' => 'cors']);
        $client_routes->post('credit-application/save/(:any)', 'DigitalRetailingController::saveCreditApplication/$1', ['filter' => 'cors']);
        $client_routes->options('(:any)', 'UpdateController::options/$1');
    });

    $client_routes->group('dealer-track', function ($client_routes) {
        $client_routes->post('receive', 'DealerTrackController::receive');
    });

    $client_routes->group('deals', function ($client_routes) {
        $client_routes->addPlaceholder('deal_import_web_extension', '(?i)dealertrack');
        $client_routes->post('import/web-extension/(:deal_import_web_extension)', 'DealerTrackController::webExtension/$1');
        $client_routes->post('show/web-extension/private-deal/(:alphanum)', 'DealController::completeDealPayloadForExtension/$1');
    });

    $client_routes->group('web-extension', function ($client_routes) {
        $client_routes->get('token/validate', 'ExtensionController::validateApiToken');
        $client_routes->get('vendors/enabled', 'ExtensionController::getEnabledVendorsList');
    });
});
