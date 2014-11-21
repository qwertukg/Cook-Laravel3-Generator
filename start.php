<?php

Autoloader::namespaces(array(
	'Cook' => Bundle::path('cook').'Cook',
));

IoC::register('Generator', function()
{
	return new Cook\Generator;
});

IoC::register('Constructor', function()
{
	return new Cook\Constructor;
});

IoC::singleton('ConstructorStorage', function()
{
	return new Cook\Constructor\Storage;
});

// Redeclare Migrator
IoC::register('task: migrate', function()
{
	$database = new Laravel\CLI\Tasks\Migrate\Database;
	$resolver = new Laravel\CLI\Tasks\Migrate\Resolver($database);

	return new Cook\Laravel\Migrator($resolver, $database);
});	
