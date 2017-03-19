<?php /**
* Konphigo: simple configuration
* @author Kaloyan Tsvetkov (KT) <kaloyan@kaloyan.info>
* @package Konphigo
* @link https://github.com/kktsvetkov/Konphigo/
* @license http://opensource.org/licenses/LGPL-3.0 GNU Lesser General Public License, version 3.0
*/

namespace Konphigo;

/**
* Configuration Facade
*
* Static methods for reading the configuration values
*/
class Konphigo
{
	/**
	* Load new configuration with array union
	* @see \Konphigo\Konphigo::load()
	*/
	const LOAD_MODE_UNION = 0;

	/**
	* Load new configuration with {@link array_merge()}
	* @see \Konphigo\Konphigo::load()
	*/
	const LOAD_MODE_MERGE = 1;

	/**
	* Load new configuration with {@link array_replace()}
	* @see \Konphigo\Konphigo::load()
	*/
	const LOAD_MODE_REPLACE = 2;

	/**
	* @var array loaded configuration settings
	*/
	protected static $config = array();

	/**
	* Introduce new configuration settings from an array
	*
	* There are several ways to append the new configuration. This is
	* controlled by the $mode argument. By default $mode is empty, and this
	* will add the new values with an array union. Other accepted values
	* are 1 for {@link array_merge()} and 2 for {@link array_replace()}
	*
	* @param array $config
	* @param integer $mode (optional) how to introduce the new config;
	*	"0" for array union, "1" for array_merge() and "2" for
	*	array_replace(); will trigger E_WARNING if the $mode value
	*	is not recognized
	*/
	public static function load(array $config, $mode = self::LOAD_MODE_UNION)
	{
		if (empty($mode))
		{
			self::$config += $config;
		} else
		if (1 == self::LOAD_MODE_MERGE)
		{
			self::$config = array_merge(self::$config, $config);
		} else
		if (2 == self::LOAD_MODE_REPLACE)
		{
			self::$config = array_replace(self::$config, $config);
		} else
		{
			trigger_error(
				"Unknown \$mode \"{$mode}\", provide empty value, 1 or 2!",
				E_WARNING
			);
			self::$config += $config;
		}
	}

	/**
	* Return a configuration value
	*
	* @param string $name name of the configuration value; empty value
	*	for this argument will make this method return all
	*	configuration values
	* @param mixed $default (optional) failsafe value if the setting is empty
	* @return mixed
	*/
	public static function get($name = null, $default = null)
	{
		if (empty($name))
		{
			return self::$config;
		}

		return empty(self::$config[$name])
			? $default
			: self::$config[$name];
	}
}
