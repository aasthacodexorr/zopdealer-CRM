<?php 
namespace App\Libraries;

use CodeIgniter\Cache\Handlers\FileHandler;

class PersistantFileHandler extends FileHandler
{
    /**
	 * Does the heavy lifting of actually retrieving the file 
	 *
	 * @param string $key
	 *
	 * @return boolean|mixed
	 */
	protected function getItem(string $key)
	{
		if (! is_file($this->path . $key))
		{
			return false;
		}

		$data = unserialize(file_get_contents($this->path . $key));
		return $data;
	}
}
