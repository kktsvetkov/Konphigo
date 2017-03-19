<?php /**
* Konphigo: simple configuration
* @author Kaloyan Tsvetkov (KT) <kaloyan@kaloyan.info>
* @package Konphigo
* @link https://github.com/kktsvetkov/Konphigo/
* @license http://opensource.org/licenses/LGPL-3.0 GNU Lesser General Public License, version 3.0
*/

namespace Konphigo\Read;

/**
* Quick and dirty helper for loading configuration values with my own spin
*
* What I need is to load configuration data from PHP files, but identify them
* differently: not from their filename, but with a $name and to have them
* read from a particular folder
*
*
* Uses the same PHP format as {@link \Konphigo\Read\PHP}:
* <?php return array('a' => 2, 'b' => 'y');
*
* Example of how to use this with {@link \Konphigo\Konphigo::load()}:
* \Konphigo\Konphigo::load(
* 	\Konphigo\Read\KT::load(
* 		'kt',
* 		array('B' => 303, 'CC' => 'ddd')
* 	)
* );
*/
class KT
{
	/**
	* @var string the folder from which the config files are read
	*/
	protected static $folder;

	/**
	* Get\Set the config files folder
	*
	* @param string $folder (optional) the folder where the config files
	*	are, if you want to change it
	* @return string
	*/
	public static function folder($folder = null)
	{
		if (!empty($folder))
		{
			self::$folder = $folder;
		}

		if (!isset(self::$folder))
		{
			if (!empty($_SERVER['argv'][0]) && file_exists($_SERVER['argv'][0]))
			{
				self::$folder = dirname($_SERVER['argv'][0]);
			} else
			{
				self::$folder = getcwd();
			}
		}

		return self::$folder;
	}

	/**
	* Reads configuration values using $name to identify
	* what file should be read
	*
	* @param string $name identify what to load
	* @param array $default (optional) default failsafe values
	* @return array what was actually loaded
	*
	* @uses \Konphigo\Read\PHP::load()
	*/
	public static function load($name, array $default = [])
	{
		if (empty($name))
		{
			$name = 'machine.' . php_uname('n');
		}

		$file = self::folder()
			. DIRECTORY_SEPARATOR
			. '.config.'
			. self::id($name)
			. '.php';

		return \Konphigo\Read\PHP::load($file, $default);
	}

	/**
	* Creates an id that is safe to use in filenames
	* @param string $id
	* @return string
	*/
	public static function id($id)
	{
		return preg_replace(
			'~\W+~Uis', '.',
			preg_replace('~^\W*(.+)\W*$~', '\\1', $id)
		);
	}
}
