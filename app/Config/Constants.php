<?php

//--------------------------------------------------------------------
// App Namespace
//--------------------------------------------------------------------
// This defines the default Namespace that is used throughout
// CodeIgniter to refer to the Application directory. Change
// this constant to change the namespace that all application
// classes should use.
//
// NOTE: changing this will require manually modifying the
// existing namespaces of App\* namespaced-classes.
//
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
|--------------------------------------------------------------------------
| Composer Path
|--------------------------------------------------------------------------
|
| The path that Composer's autoload file is expected to live. By default,
| the vendor folder is in the Root directory, but you can customize that here.
*/
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
|--------------------------------------------------------------------------
| Timing Constants
|--------------------------------------------------------------------------
|
| Provide simple ways to work with the myriad of PHP functions that
| require information to be in seconds.
*/
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2592000);
defined('YEAR')   || define('YEAR', 31536000);
defined('DECADE') || define('DECADE', 315360000);

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

defined('ORGANIZATION')      || define('ORGANIZATION', 'zopdealer'); // highest automatically-assigned error code
defined('ZD_DOMAIN')      || define('ZD_DOMAIN', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');

defined('UPLOAD_FOLDER') || define('UPLOAD_FOLDER', 'upload'); //For Uploaded Files
defined('API_IMPORT_FOLDER') || define('API_IMPORT_FOLDER', 'third_party_api_import');  //For API Import Files
defined('OVERLAY_FOLDER') || define('OVERLAY_FOLDER', 'overlay'); //For Overlay Files
defined('ROOT_IMAGE') || define('ROOT_IMAGE', 'image'); //For Images Such as Syndication Companies 
defined('ROOT_FILE') || define('ROOT_FILE', 'file'); //For Attachment FIles.
defined('DVI_FILE') || define('DVI_FILE', 'digital_vehicle_inspection');
defined('ERROR_RESOURCE_PERMISSION') || define('ERROR_RESOURCE_PERMISSION', 'You do not have permission to access the resource.'); //For Resource Permission
defined('ZD_VERSION') || define('ZD_VERSION', '31.14.2'); //Version of Zopdealer
defined('SYNDICATION_EXPORT') || define('SYNDICATION_EXPORT',  ROOTPATH . '/public/media/export/'); // For Syndication export files


defined('KBB_DEALER_PRICE_TYPE') || define('KBB_DEALER_PRICE_TYPE', 1); //KBB DEALER PRICE TYPE

defined('MODULE_LEAD') || define('MODULE_LEAD', 'Lead');
defined('MODULE_DEAL') || define('MODULE_DEAL', 'Deal');

defined('ROOT_BUSINESS_LOGO') || define('ROOT_BUSINESS_LOGO', ROOTPATH . "/public/media/logo/"); //For Business Logo Path
defined('ZD_IST_DATE_FORMAT') || define('ZD_IST_DATE_FORMAT', 'd/m/Y');

defined('NOTIFICATION_QUEUE') || define('NOTIFICATION_QUEUE', 'NOTIFY');
defined('NOTIFICATION_PUSH_ROUTE') || define('NOTIFICATION_PUSH_ROUTE', 'NOTIFY_PUSH');
defined('NOTIFICATION_SMS_ROUTE') || define('NOTIFICATION_SMS_ROUTE', 'NOTIFY_SMS');
defined('NOTIFICATION_EMAIL_ROUTE') || define('NOTIFICATION_EMAIL_ROUTE', 'NOTIFY_EMAIL');
defined('WORKFLOW_ROUTE') || define('WORKFLOW_ROUTE', 'WORKFLOW_ROUTE');
defined('SCHEDULE_ROUTE') || define('SCHEDULE_ROUTE', 'SCHEDULE_ROUTE');
defined('REPORT_PROCESS_ROUTE') || define('REPORT_PROCESS_ROUTE', 'REPORT_PROCESS_ROUTE');
defined('DOWNLOAD_THIRD_PARTY_IMAGE_ROUTE') || define('DOWNLOAD_THIRD_PARTY_IMAGE_ROUTE', 'DOWNLOAD_THIRD_PARTY_IMAGE');
defined('DOWNLOAD_AI_STUDIO_IMAGE_ROUTE') || define('DOWNLOAD_AI_STUDIO_IMAGE_ROUTE', 'DOWNLOAD_AI_STUDIO_IMAGE');
