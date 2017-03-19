<?php /**
* Konphigo: simple configuration
* @author Kaloyan Tsvetkov (KT) <kaloyan@kaloyan.info>
* @package Konphigo
* @link https://github.com/kktsvetkov/Konphigo/
* @license http://opensource.org/licenses/LGPL-3.0 GNU Lesser General Public License, version 3.0
*/

namespace Konphigo\Read;

/**
* Quick and dirty helper for loading configuration values from JSON.
*
* Example of how to use this with {@link \Konphigo\Konphigo::load()}:
* \Konphigo\Konphigo::load(
* 	\Konphigo\Read\JSON::load(
* 		'config-test.json',
* 		array('BB' => 44, 'ddd' => 'XYZ')
* 	)
* );
*/
class JSON
{
	/**
	* Reads configuration values from including a file with JSON inside.
	*
	* @param string $file which php file to load
	* @param array $default (optional) default failsafe values
	* @return array what was actually loaded
	*/
	public static function load($file, array $default = [])
	{
		if (!file_exists($file))
		{
			throw new \RuntimeException(
				"Config file '{$file}' not found."
			);
	    	}

		if (!is_readable($file))
		{
			throw new \RuntimeException(
				"Config file '{$file}' is not readable."
			);
	    	}

		$json = file_get_contents($file);
		$config = json_decode($json, true);
		return array_merge($default, (array) $config);
	}
}
