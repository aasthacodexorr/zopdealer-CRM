<?php 
namespace App\Libraries;

use CodeIgniter\Cache\Handlers\PredisHandler;

/**
 * PersistantRedisHandler This Handler makes the redis cache persistant.
 */
class PersistantRedisHandler extends PredisHandler
{
    	/**
	 * Saves an item to the cache store.
	 *
	 * @param string  $key   Cache item name
	 * @param mixed   $value The data to save
	 * @param integer $ttl   Time To Live, in seconds (default 60)
	 *
	 * @return mixed
	 */
	public function save(string $key, $value, int $ttl = null)
	{
		switch ($data_type = gettype($value))
		{
			case 'array':
			case 'object':
				$value = serialize($value);
				break;
			case 'boolean':
			case 'integer':
			case 'double': // Yes, 'double' is returned and NOT 'float'
			case 'string':
			case 'NULL':
				break;
			case 'resource':
			default:
				return false;
		}

		if (! $this->redis->hmset($key, ['__ci_type' => $data_type, '__ci_value' => $value]))
		{
			return false;
		}


		return true;
	}
}