<?php

Route::get('migrate', array('as' => 'migrate', function()
{
	print_r(shell_exec('php artisan migrate --tpl=EngineBundle'));
}));

Route::get('rollback', array('as' => 'rollback', function()
{
	print_r(shell_exec('php artisan migrate:rollback --tpl=EngineBundle'));
}));