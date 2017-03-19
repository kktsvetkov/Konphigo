<?php /**
* Autoload helper, if used outside Composer
*/
function Konphigo_autoload($class)
{
	$c = explode('\\', $class);
	if (($c[0] == 'Konphigo'))
	{
		array_shift($c);
		$_ = __DIR__ . '/src/' . join('/', $c) . '.php';
		if (file_exists($_))
		{
			include $_;
		}
	}
}

spl_autoload_register('Konphigo_autoload');
