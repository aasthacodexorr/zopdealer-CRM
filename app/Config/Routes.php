<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes(true);

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */


// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

$routes->group('api', function ($routes) {
	$routes->group('admin', function ($routes) {
		$routes->add('login', 'AuthenticationController::login');
		$routes->add('exchange', 'AuthenticationController::exchange');
		$routes->add('setuser', 'AuthenticationController::set_cookies');
		$routes->add('details', 'AuthenticationController::details', ['filter' => 'auth']);
		$routes->add('logout', 'AuthenticationController::logout');
		$routes->add('v2logout', 'AuthenticationController::v2logout');
		$routes->get('get/connection', 'AuthenticationController::getConnectionId');
		$routes->get('get/cdn', function () {
			header('Content-Type: application/json');
			$myJSON = json_encode(['name' => getenv('image.cdn')]);
			echo $myJSON;
			exit;
		});
	});

	$routes->group('clients', function ($routes) {
		$routes->post('create', 'ClientController::create', ['filter' => 'auth']);
		$routes->post('delete/(:alphanum)', 'ClientController::delete/$1', ['filter' => 'auth']);
		$routes->post('/', 'ClientController::index', ['filter' => 'auth']);
		$routes->get('cache/update', 'ClientController::updateCacheForClients', ['filter' => 'auth']);
		$routes->get('status/active', 'ClientController::checkIfClientIsInDeletionQueue', ['filter' => 'cors']);
	});

	$routes->group('syndication/companies', function ($routes) {
		$routes->post('create', 'GlobalSyndicationCompanyController::create', ['filter' => 'auth']);
		$routes->get('/', 'GlobalSyndicationCompanyController::index', ['filter' => 'auth']);
		$routes->get('all', 'GlobalSyndicationCompanyController::index');
		$routes->get('remove/(:alphanum)', 'GlobalSyndicationCompanyController::removeSyndicationCompanyForClient/$1', ['filter' => 'auth']);
		$routes->get('inventory/remove/(:alphanum)', 'GlobalSyndicationCompanyController::removeSyndicationCompanyFromClientInventory/$1', ['filter' => 'auth']);
		$routes->get('inventory/add/(:alphanum)', 'GlobalSyndicationCompanyController::addSyndicationCompanyForClientInventory/$1', ['filter' => 'auth']);
		$routes->post('update', 'GlobalSyndicationCompanyController::updateSyndicationCompanyForClient', ['filter' => 'auth']);
		$routes->post('update/status/(:alphanum)', 'GlobalSyndicationCompanyController::updateStatusOfSyndicationCompanyForClient/$1', ['filter' => 'auth']);
		$routes->options('(:any)', 'GlobalSyndicationCompanyController::options/$1');
	});

	$routes->group('templates', function ($routes) {
		$routes->post('create', 'LeadTemplateController::create', ['filter' => 'auth']);
		$routes->get('show/(:alphanum)', 'LeadTemplateController::show/$1', ['filter' => 'auth-cors']);
		$routes->get('render/(:alphanum)', 'LeadTemplateController::render/$1');
		$routes->post('update/(:alphanum)', 'LeadTemplateController::update/$1', ['filter' => 'auth']);
		$routes->get('/', 'LeadTemplateController::index', ['filter' => 'auth-cors']);
		$routes->get('all', 'LeadTemplateController::index', ['filter' => 'auth-cors']);
		$routes->options('(:any)', 'LeadTemplateController::options/$1');
	});

	$routes->group('service', function ($routes) {
		$routes->get('trade-in-inventory/(:any)', 'KellyBlueBookController::index/$1');
		$routes->get('kbb/all/years', 'KellyBlueBookController::getAllYears');
		$routes->get('kbb/all/makes/year/(:any)', 'KellyBlueBookController::getAllMakes/$1');
		$routes->get('kbb/all/models/year/(:any)/makeID/(:any)', 'KellyBlueBookController::getAllModels/$1/$2');
		$routes->get('kbb/all/trims/year/(:any)/modelID/(:any)', 'KellyBlueBookController::getAllTrims/$1/$2');
	});

	/*** Image Service Here */
	$routes->add('service/images/(:num)/(:alphanum)/(:any)', function ($width, $height, $file_path) {
		$manager = new \Intervention\Image\ImageManager(array('driver' => 'imagick'));
		header('Content-Type: image/png');
		$img =  $manager->make($file_path)->resize($width, $height, function ($constraint) {
			$constraint->aspectRatio();
			$constraint->upsize();
		});
		echo $img->encode('png');
		exit;
	});

	$routes->group('syndication', function ($routes) {
		$routes->post('create', 'SyndicationController::create', ['filter' => 'auth']);
		$routes->get('list', 'SyndicationController::list', ['filter' => 'auth']);
		$routes->post('listing', 'SyndicationController::feedList', ['filter' => 'auth']);
		$routes->get('queue/generate/(:alphanum)', 'SyndicationController::generateQueue/$1', ['filter' => 'auth']);
		$routes->get('queue/list', 'SyndicationController::getQueue', ['filter' => 'auth']);
		//	$routes->get('queue/clean', 'SyndicationController::cleanQueue', ['filter' => 'auth']);
		$routes->get('queue/process', 'SyndicationController::processQueue', ['filter' => 'auth']);
		$routes->get('process/autotraderglobal', 'SyndicationController::processAutotraderGlobal');
        $routes->get('process/autotraderglobalnpv', 'SyndicationController::processAutotraderGlobalNpv');
		$routes->get('process/siriusxm', 'SyndicationController::processSiriusxm');
		$routes->get('process/plaglobal', 'SyndicationController::processPlaGlobal');
		$routes->get('queue/skip/(:num)', 'SyndicationController::skipQueue/$1', ['filter' => 'auth']);
		$routes->get('queue/sprocess', 'SyndicationController::processSocialQueue', ['filter' => 'auth']);
		$routes->get('operation/(:alphanum)', 'SyndicationController::getCommand/$1', ['filter' => 'auth']);
		$routes->get('queue/process/(:num)', 'SyndicationController::processQueue/$1', ['filter' => 'auth']);
		$routes->get('generate/syndication/(:alphanum)', 'SyndicationController::generateit/$1');
		$routes->get('process/syndication', 'SyndicationController::processit');
	});

	$routes->group('invoice', function ($routes) {
		$routes->get('overdue', 'InvoiceController::overdueInvoices');
	});

	$routes->group('reviews', function ($routes) {
		$routes->get('update', 'ReviewsController::updateReviews');
	});

	$routes->group('mailbox', function ($routes) {
		$routes->get('emailtest', 'MailboxController::emailtest');
	});

	$routes->group('adflead', function ($routes) {
		$routes->get('process', 'AdfleadController::process');
	});

	$routes->group('fbflead', function ($routes) {
        $routes->get('process/all', 'LeadProcessingController::processAllSources');
        // not in use but keeping it for future use
        $routes->get('process/(:alpha)', 'LeadProcessingController::processLeadsBySource/$1');
    });

});

$routes->get('getfbtoken', 'FbCallbackController::getFbToken');
$routes->get('getigtoken', 'IgCallbackController::getIgToken');
$routes->get('removefbtoken', 'FbCallbackController::unlinkAccount');
$routes->get('removeigtoken', 'IgCallbackController::unlinkAccount');

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

/**
 * Configuring routes for the tenant applications.
 *
 * **/
if (file_exists(ROOTPATH . 'Tenant/app/Config/Routes.php')) {
	require ROOTPATH . 'Tenant/app/Config/Routes.php';
}

/**
 * Configuring routes for the cardora applications.
 *
 * **/
if (file_exists(ROOTPATH . 'Cardora/app/Config/Routes.php')) {
	require ROOTPATH . 'Cardora/app/Config/Routes.php';
}
