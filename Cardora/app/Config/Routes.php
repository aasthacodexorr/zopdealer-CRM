<?php
namespace Cardora\Config;

// RouteCollection class
$cardora = \Config\Services::routes(true);
$cardora->setTranslateURIDashes(false);
$cardora->set404Override();
$cardora->setAutoRoute(true);

$cardora->setDefaultNamespace('Cardora\app\Controllers');

$cardora->group('cardora', function ($cardora) {
    $cardora->group('v1', function ($cardora) {
        $cardora->get('inventory', 'CinventoryController::index');
        $cardora->get('inventory/show/(:alphanum)', 'CinventoryController::show/$1');
        $cardora->get('inventory/filters', 'CinventoryController::filters');
    });
});