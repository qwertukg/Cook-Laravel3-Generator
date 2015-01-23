Cook Laravel Generator
===================

Description
-----------

Improves Laravel Migrator.
Allows generating bundles, with all stuff, from laravel migrations, by your own templates.

Instalation
-----------


Before all you must add Cook bundle to your `bundles.php` file like this:

    return array(
    	'cook'
    );

After this start Cook manually. Add `Bundle::start('cook');` at the begining of your main route.php file.

Last step is installing Cook Storage. It's not allows regenerate changing files. Open CLI and type php artisan migrate:install_cook
