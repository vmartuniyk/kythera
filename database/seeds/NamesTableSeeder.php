<?php

/**
 * @author virgilm
 *
 */
class NamesTableSeeder extends DatabaseSeeder
{
    public function run()
    {
        //empty
        DB::table("names")->truncate();
        
        //import
        $items = [];
        //$rows = DB::connection('import')->select('select persons_id as id, firstname, middlename, lastname from names where lastname != "";');
        $rows = DB::connection('import')->select('select id, name as lastname from namebox_entries;');
        foreach ($rows as $row) {
            $item = [];
            foreach (get_object_vars($row) as $k => $v) {
                $item[$k] = $v;
            }
            $items[] = $item;
        }

        //insert
        DB::table("names")->insert($items);
    }
}
