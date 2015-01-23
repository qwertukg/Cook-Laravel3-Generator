Cook Laravel3 Generator
===================

Description
-----------

Improves Laravel3 Migrator.
Allows generating bundles, with all stuff, from Laravel3 migrations, by your own templates.

Instalation
-----------

> All this must be done after full Laravel3 instalation.

Before all you must add Cook bundle to your `bundles.php` file like this:
```php
return array(
    'cook'
);
```
After this start Cook manually in your main route.php file:
```php
Bundle::start('cook');
``` 
at the begining of your main route.php file.

Last step, is installing Cook Storage. It's not allows regenerate changing files. Open CLI and run Artisan command: 
```bash
php artisan migrate:install_cook
```

**Instalation done!**

Usage
-----

> Cook allready have template for [Laravel
> Engine](https://github.com/mobileka/laravel-engine), but can write
> your own template (looks next paragraph) for any purpose, even for
> nativ Larevel3.

Create empty budnle and write usual migration for him, like this:
```php
class Attributes_Create_Attributes_Table {

	public function up()
	{
		Schema::table('attributes', function($t)
		{
			$t->create();
			$t->increments('id');
			$t->string('title');
			$t->text('description');
			$t->integer('value_id')->unsigned();
			$t->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('attributes');
	}

}
```

To afford Cook generating code for this bundle - replace native Laravel Schema by Cook Schema. Just add:
```php
use Cook\Laravel\Schema;
``` 

And then you wil run you migration, just add `--tpl=EngineBundle` after `migrate` command. And Cook create all code for you! 

> **EngineBundle** - is name of template folder.

> Cook generating code just for migration **where Schema is replaced**.

> Cook automaticly destroy files when you rollbak migration.

> Cook **do not delete/replace** files changes by you.

Templating
----------

Comming soon...

Look [EngineBundle](https://github.com/qwertukg/Cook-Laravel-Generator/tree/master/Cook/Templates/EngineBundle) there are lot of examples ;)

<!---
Templates has own easy syntax. Write/change template easily.

Let's create new template who generate just one language file:

- go to **Cook\Templates** folder and create **MyTemplate** folder over there
- by Laravel3 convention, language file locate in **language\ru** folder, make this
- make **deafult.tpl** file, it will be static template for our language
- write static content over there, like this: 
```php
return array(
	<labels>
);
```
> ```<labels>``` is **token**. It must by replaced by **replacer**

- create **DefaultReplacer.php** file. Where filename must be consits of two parts: first like .tpl filename and must have suffix **Replacer**
-->

Cool stuff
----------

Comming soon...
