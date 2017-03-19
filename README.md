# Konphigo
Konphigo is a small PHP5/PHP7 library for working with configuration.

It is meant to be very easy to use. I've had experience with several other configuration implementations and they were quite clumsy and overdressed for my taste. I prefer dead-simple and re-usable.

Konphigo is meant to be used as "facade" -- a class with static methods.

## Basic use
You read configuration data by calling the `\Konphigo\Konphigo::get()` method. First argument is the name of what you want to read.

	$a = \Konphigo\Konphigo::get('a');

You get all the loaded configuration data by using an empty first argument.

	$all = \Konphigo\Konphigo::get();

The second argument is a fallback default value, if what you want to read is not there.

	$a = \Konphigo\Konphigo::get('a', 123);

Since you can have both scalars and arrays inside the configuration, that second argument can be array as well.

	$skip = \Konphigo\Konphigo::get('skip', ['.svn', '.git']);

## Loading up
I want to keep library very simple. The main class, `\Konphigo\Konphigo` has a single method for loading configuration data, called `\Konphigo\Konphigo::load()`.

	\Konphigo\Konphigo::load([
		'skip' => ['.svn', '.git'],
		'tmp' => '/tmp'
		]);

The first argument is an array with the data you want to read. It is an associated array, where the keys are the names for the configuration data, and the values is the actual data.

In a lot of cases you need to load configuration data in more than one occurrence. You start loading some data, then you need to add more data; in same cases you need to replace some of the already loaded data. This is where the second argument for the `\Konphigo\Konphigo::load()` method is used. This is the "mode" used for how to handle the loaded data.

	// replacing already loaded "skip" configuration value with a new one
	//
	\Konphigo\Konphigo::load([
		'skip' => ['.svn', '.git', '.dudu']
		],
		Konphigo::LOAD_MODE_REPLACE
		);

The three options are:

 * `Konphigo::LOAD_MODE_UNION` - default value, this will get the new data and do array union with it, e.g. `$config += $data`;
 * `Konphigo::LOAD_MODE_MERGE` - new data is merged with the already loaded, e.g. `$config = array_merge($data, $config);`;
 * `Konphigo::LOAD_MODE_REPLACE` - new data will overwrite existing keys if there is a match, e.g. `$config = array_replace($data, $config);`;

 The `array_replace()` is pretty straight-forward, most people are puzzled with the difference between array union and `array_merge()`.  

## Advanced loading
Inside this library there are several classes under the `Konphigo\Read` namespace. They are designed to read configuration data from various media. These "reader" classes do not work with `Konphigo` class, instead they return arrays from what was read. I did this de-coupling on purpose, since as I stated at the beginning, I wanted this to be dead-simple.

All of the `Konphigo\Read` classes read the data with a static `::load()` methods. First method is usually how to identify the media to read from. The second argument is optional, and it is to provide default values for what was read; in this way even if you read from an empty media, there are still going to be configuration data loaded from these default values.

#### Konphigo\Read\PHP
This reads the data from PHP files.

	 \Konphigo\Konphigo::load(
	 	\Konphigo\Read\PHP::load(
	 		'config-test.php',
	 		array('B' => 303, 'CC' => 'ddd')
	 	)
	 );

These files need to follow a format, which will allow to get the data by including them.

	<?php return array(
		'a' => 11,
		'B' => 202,
	);

#### Konphigo\Read\JSON 
This reads from files with JSON-encoded data inside it.

	 \Konphigo\Konphigo::load(
	 	\Konphigo\Read\JSON::load(
	 		'config-test.json',
	 		array('B' => 303, 'CC' => 'ddd')
	 	)
	 );

There are no requirements about the format, it just has to be valid JSON.

One thing to consider when you use JSON files for configuration, is that those are readable if put in web-accessible folders. For example, if your configuration is inside the web root folder, someone will be able to read it by loading up _www.example.com/config.json_. This is different for PHP files, since if you try to read them (accessing _www.example.com/config.php_) you will get nothing, since the PHP file returns value and does not print anything.

#### Konphigo\Read\KT
This reads from files with PHP with my own twist put on it. This is what I use for my projects. It is using `Konphigo\Read\PHP` to read the data. In addition to that it transforms a "name" into the PHP file to load.

	 \Konphigo\Konphigo::load(
	 	\Konphigo\Read\KT::load(
	 		'kt',
	 		array('B' => 303, 'CC' => 'ddd')
	 	)
	 );

This class reads all the PHP file from a folder, which you can get/set with `\Konphigo\Read\KT::folder()`. 
If the name for the media to load is empty, the name from the machine is used to compose it: `$name = 'machine.' . php_uname('n');`. This allows me to store machine-specific configuration inside the version-control-system, and not worry about configuration clashes.

## Few tricks
Here are some ideas how to make working with this easier

#### class_alias()
Create class alias to `\Konphigo\Konphigo` to type less, e.g.

	class_alias('Konphigo', '\Konphigo\Konphigo');

In this way you don't have to use the long `\Konphigo\Konphigo` all the time, nor to explicitly declare it as "`use \Konphigo\Konphigo as Konphigo;`" each time you want to use it.
