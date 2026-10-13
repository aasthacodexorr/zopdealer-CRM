<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
	// Makes reading things below nicer,
	// and simpler to change out script that's used.
	public $aliases = [
		'csrf'     => \CodeIgniter\Filters\CSRF::class,
		'toolbar'  => \CodeIgniter\Filters\DebugToolbar::class,
		'honeypot' => \CodeIgniter\Filters\Honeypot::class,
		'auth' 		=> [
			\App\Filters\AuthFilter::class,
            \App\Filters\AuditFilter::class,
			\App\Filters\Logger::class,
		],
		'auth-elevated-access' 		=> [
			\App\Filters\AuthFilter::class,
            \App\Filters\AuditFilter::class,
            \App\Filters\RestrictedRoleAccess::class,
			\App\Filters\Logger::class,
		],
		'auth-service' 		=> [
			\App\Filters\AuthFilter::class,
            \App\Filters\AuditFilter::class,
            \App\Filters\AuthService::class,
			\App\Filters\DynamicThrottleService::class,
			\App\Filters\Logger::class,
		],
		'restricted-auth-service' 		=> [
			\App\Filters\AuthFilter::class,
            \App\Filters\AuditFilter::class,
            \App\Filters\RestrictedRoleAccess::class,
			\App\Filters\AuthService::class,
			\App\Filters\DynamicThrottleService::class,
			\App\Filters\Logger::class,
		],
		'cors' 		=> \App\Filters\Cors::class,
		'auth-cors' => [
			\App\Filters\AuthFilter::class,
            \App\Filters\AuditFilter::class,
            \App\Filters\Cors::class,
		],
		'otp-throttle' => \App\Filters\OTPThrottle::class,
		'captcha-solver-throttle' => \App\Filters\CaptchaSolverThrottle::class,
		'protected-pdf-parser-throttle' =>
		[
			\App\Filters\AuthFilter::class,
            \App\Filters\AuditFilter::class,
            \App\Filters\PDFParserThrottle::class,
			\App\Filters\Logger::class
		],
		'protected-vehicle-description-rewriter-throttle' => [
			\App\Filters\AuthFilter::class,
            \App\Filters\AuditFilter::class,
            \App\Filters\VehicleDescriptionRewriterThrottle::class,
			\App\Filters\Logger::class
		],
		'active-tenant-account' 		=> [
			\App\Filters\TenantAccountFilter::class,
		],
		'protected-api-throttle' => [
			\App\Filters\AuthFilter::class,
            \App\Filters\AuditFilter::class,
            \App\Filters\APIThrottle::class,
			\App\Filters\Logger::class

		],
		'protected-places-api-throttle' => [
			\App\Filters\AuthFilter::class,
            \App\Filters\AuditFilter::class,
            \App\Filters\GooglePlacesThrottle::class,
			\App\Filters\Logger::class

		],
		'protected_ocr_parser_throttle' => [
			\App\Filters\AuthFilter::class,
            \App\Filters\AuditFilter::class,
            \App\Filters\OCRParseThrottle::class,
			\App\Filters\Logger::class

		],
		'protected-places-api-ip-throttle' => [
			\App\Filters\GooglePlacesIPBasedThrottle::class,
		],
		'public-vin-decoder-throttle' => \App\Filters\VinDecoderThrottle::class,
        'public-api-throttle' => \App\Filters\PublicAPIThrottle::class,
	];

	// Always applied before every request
	public $globals = [
		'before' => [
			//'honeypot'
			// 'csrf',
		],
		'after'  => [
			'toolbar',
			//'honeypot'
		],
	];

	// Works on all of a particular HTTP method
	// (GET, POST, etc) as BEFORE filters only
	//     like: 'post' => ['CSRF', 'throttle'],
	public $methods = [];

	// List filter aliases and any before/after uri patterns
	// that they should run on, like:
	//    'isLoggedIn' => ['before' => ['account/*', 'profiles/*']],
	public $filters = [];
}
