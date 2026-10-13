<?php

namespace Config;

use App\Libraries\AuditLog\AuditLog;
use App\Libraries\Channel\AbstractChannelFactory;
use App\Libraries\WorkFlow\WorkFlowService;
use App\Policies\AuthPolicy;
use App\Policies\TenantAccountPolicy;
use CodeIgniter\Cache\CacheFactory;
use CodeIgniter\Config\Services as CoreServices;
use CodeIgniter\Throttle\Throttler;
use Tenant\app\Libraries\Audit\AuditContext;
use Tenant\app\Libraries\Audit\AuditManager;
use Tenant\app\Libraries\Audit\ChangeDetector;

require_once SYSTEMPATH . 'Config/Services.php';

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends CoreServices
{

	public static function authPolicy($getShared = true)
	{
		if ($getShared) {
			return static::getSharedInstance('authPolicy');
		}

		return new AuthPolicy();
	}

	public static function tenantAccountPolicy($getShared = true)
	{
		if ($getShared) {
			return static::getSharedInstance('tenantAccountPolicy');
		}

		return new TenantAccountPolicy();
	}

	public static function channelService($getShared = true)
	{
		if ($getShared) {
			return static::getSharedInstance('channelService');
		}

		return new AbstractChannelFactory();
	}

	public static function throttler(bool $getShared = true)
	{
		if ($getShared) {
			return static::getSharedInstance('throttler');
		}

		return new Throttler(CacheFactory::getHandler(new NonPersistantCache()));
	}

	public static function workFlowService(bool $getShared = true)
	{
		if ($getShared) {
			return static::getSharedInstance('workFlowService');
		}

		return new WorkFlowService();
	}

    public static function auditContext(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('auditContext');
        }

        return new AuditContext();
    }

    public static function changeDetector(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('changeDetector');
        }
        return new ChangeDetector();
    }

    public static function auditManager(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('auditManager');
        }
        return new AuditManager(service('auditContext'));
    }

    public static function auditLogAPIService(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('auditLogAPIService');
        }
        return new AuditLog();
    }
}
