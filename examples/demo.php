<?php /**
* Demonstration of what you can do with Konphigo
*/

error_reporting(E_ALL);
date_default_timezone_set('Europe/Sofia');

/* you do not need this if you are using composer */
require dirname(__FILE__) . '/../autoload.php';

use Konphigo\Konphigo as Konphigo;

/* Load directly an array */
Konphigo::load(array(
	'a' => 1,
	'b' => 'z',
	'c' => range('10', '20')
));

/* get all configuration values */
$a = Konphigo::get();
echo "* All loaded configuration values: ";
print_r($a);
echo "\r\n";

/* fall back to default value if the key is not found */
$b = Konphigo::get('A', -1);
echo "* 'A': ";
var_dump($b);
echo "\r\n";

/* replace "b" configuration */
$old = Konphigo::get('b');
Konphigo::load(array('b' => 'x'), 2);
$new = Konphigo::get('b');
echo "* old and new values for 'b': ";
print_r(array('old' => $old, 'new' => $new));
echo "\r\n";

/* load config data from a PHP file */
$loaded = \Konphigo\Read\PHP::load(
	'config-test.php',
	array('B' => 303, 'CC' => 'ddd')
	);
echo "* what was loaded from 'config-test.php': ";
print_r($loaded);
echo "\r\n";

/* load config data the KT way */
$kt = \Konphigo\Read\KT::load('kt', array('k1' => 'K1', 'k2' => 'KK22'));
echo "* what was loaded from \\Konphigo\\Read\\KT::load('kt'): ";
print_r($kt);
echo "\r\n";

/* load config data from JSON */
$json = \Konphigo\Read\JSON::load(
	'config-test.json',
	array('BB' => 44, 'ddd' => 'XYZ')
	);
echo "* what was loaded from 'config-test.json': ";
print_r($json);
echo "\r\n";
