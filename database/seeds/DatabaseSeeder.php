<?php

/**
 * @author virgilm
 *
 */
class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        //$this->call("PostTableSeeder");
        //$this->call("NamesTableSeeder");
        $this->call("FoldersTableSeeder");
    }
}
