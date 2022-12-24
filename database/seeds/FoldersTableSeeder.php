<?php

/**
 * @author virgilm
 *
 */
class FoldersTableSeeder extends DatabaseSeeder
{
    public function run()
    {
        $items = [
            ["title"   => "Header menu"],
            ["title"   => "Main menu"],
            ["title"   => "Sub menu"],
            ["title"   => "Footer menu"],
        ];

        DB::table("folders")->insert($items);
    }
}
