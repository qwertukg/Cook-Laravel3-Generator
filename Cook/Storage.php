<?php namespace Cook;

use Laravel\Database as DB;

class Storage {

	protected static $algo = 'md5';

	// Log a file hash in the storage table.
	public function log($migration, $file, $result)
	{
		$hash = hash(static::$algo, $result);

		return  DB::table('cook_storage')->insert(compact('migration', 'file', 'hash'));
	}

	// Delete a row from the storage table.
	public function delete($migration, $file, $result)
	{
		$hash = hash(static::$algo, $result);

		return  DB::table('cook_storage')->where_migration_and_file_and_hash($migration, $file, $hash)->delete();
	}

	// Create the database file hashes storage table used by Generator.
	public static function install()
	{
		Laravel\Schema::table('cook_storage', function($table)
		{
			$table->create();
			
			$table->string('migration', 200);

			$table->string('file', 200);

			$table->string('hash', 50);

			$table->primary(array('migration', 'file', 'hash'));
		});

		echo "Cook: Storage table created successfully.";
	}


}