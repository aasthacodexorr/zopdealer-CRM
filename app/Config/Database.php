<?php namespace Config;

/**
 * Database Configuration
 *
 * @package Config
 */

class Database extends \CodeIgniter\Database\Config
{
	/**
	 * The directory that holds the Migrations
	 * and Seeds directories.
	 *
	 * @var string
	 */
	public $filesPath = APPPATH . 'Database/';

	/**
	 * Lets you choose which connection group to
	 * use if no other is specified.
	 *
	 * @var string
	 */
	public $defaultGroup = 'default';


	public $app_name = '';

	/**
	 * The default database connection.
	 *
	 * @var array
	 */
	public $default = [
		'DSN'      => '',
		'hostname' => '206.81.12.206',
		'username' => '',
		'password' => '',
		'database' => '',
		'DBDriver' => 'MySQLi',
		'DBPrefix' => '',
		'pConnect' => false,
		'DBDebug'  => (ENVIRONMENT !== 'production'),
		'cacheOn'  => false,
		'cacheDir' => '',
		'charset'  => 'utf8mb4',
		'DBCollat' => 'utf8mb4_unicode_ci',
		'swapPre'  => '',
		'encrypt'  => false,
		'compress' => false,
		'strictOn' => false,
		'failover' => [],
		'port'     => 3306,
	];


	public $cardora = [
		'DSN'      => '',
		'hostname' => '206.81.12.206',
		'username' => '',
		'password' => '',
		'database' => '',
		'DBDriver' => 'MySQLi',
		'DBPrefix' => '',
		'pConnect' => false,
		'DBDebug'  => (ENVIRONMENT !== 'production'),
		'cacheOn'  => false,
		'cacheDir' => '',
		'charset'  => 'utf8mb4',
		'DBCollat' => 'utf8mb4_unicode_ci',
		'swapPre'  => '',
		'encrypt'  => false,
		'compress' => false,
		'strictOn' => false,
		'failover' => [],
		'port'     => 3306,
	];

	/**
	 * This database connection is used when
	 * running PHPUnit database tests.
	 *
	 * @var array
	 */
	public $tests = [
		'DSN'      => '',
		'hostname' => '127.0.0.1',
		'username' => 'dhaval',
		'password' => 'password',
		'database' => ':memory:',
		'DBDriver' => 'SQLite3',
		'DBPrefix' => 'db_',  // Needed to ensure we're working correctly with prefixes live. DO NOT REMOVE FOR CI DEVS
		'pConnect' => false,
		'DBDebug'  => (ENVIRONMENT !== 'production'),
		'cacheOn'  => false,
		'cacheDir' => '',
		'charset'  => 'utf8mb4',
		'DBCollat' => 'utf8mb4_unicode_ci',
		'swapPre'  => '',
		'encrypt'  => false,
		'compress' => false,
		'strictOn' => false,
		'failover' => [],
		'port'     => 3306,
	];

	//--------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();

		// Ensure that we always set the database group to 'tests' if
		// we are currently running an automated test suite, so that
		// we don't overwrite live data on accident.
		if (ENVIRONMENT === 'testing')
		{
			$this->defaultGroup = 'tests';

			// Under Travis-CI, we can set an ENV var named 'DB_GROUP'
			// so that we can test against multiple databases.
			if ($group = getenv('DB'))
			{
				if (is_file(TESTPATH . 'travis/Database.php'))
				{
					require TESTPATH . 'travis/Database.php';

					if (! empty($dbconfig) && array_key_exists($group, $dbconfig))
					{
						$this->tests = $dbconfig[$group];
					}
				}
			}
		}



		/*** 
		 *  Switching Database for the client access according to the URL 
		 *  of the subdomain using cache.
		 */
		$cache = \Config\Services::cache();
		if($cache->isSupported() && isset($_SERVER['HTTP_HOST'])){
			$urlParts = explode('.', $_SERVER['HTTP_HOST']);
			$count = count($urlParts);
			if($count > 2 && $urlParts[0] !== "www"){
				$subdomain = $urlParts[0];
				$db = $cache->get($subdomain);
				if (!empty($db) && isset($db["group"], $db["database"])) {
					$this->defaultGroup = $db["group"];
					$this->default['database'] = $db["database"];
					$this->default['charset'] = 'utf8mb4';
				} else {
					// Fallback: Query clients table directly to populate cache and switch db
					try {
						$mysqli = @new \mysqli(
							$this->default['hostname'],
							$this->default['username'],
							$this->default['password'],
							$this->default['database'],
							$this->default['port'] ?? 3306
						);
						if (!$mysqli->connect_errno) {
							$escapedSubdomain = $mysqli->real_escape_string($subdomain);
							$res = $mysqli->query("SELECT subdomain, client_hash, connection_group FROM clients WHERE subdomain = '$escapedSubdomain' AND deleted_at IS NULL LIMIT 1");
							if ($res && $row = $res->fetch_assoc()) {
								$dbData = [
									"database" => $row['subdomain'] . '_' . $row['client_hash'],
									"group"    => $row['connection_group']
								];
								$cache->save($subdomain, $dbData);
								$this->defaultGroup = $dbData["group"];
								$this->default['database'] = $dbData["database"];
								$this->default['charset'] = 'utf8mb4';
							}
							$mysqli->close();
						}
					} catch (\Throwable $e) {
						// Fallback silently to default database configuration
					}
				}
			}
		}
	}

	//--------------------------------------------------------------------

}
