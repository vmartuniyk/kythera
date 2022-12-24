<?php
//php artisan db:seed --class=DocumentTypesTableSeeder

class DocumentTypesTableSeeder extends Seeder
{

    public function run()
    {
        //empty
        DB::table("document_types")->truncate();
        
        //import
        $items = [];
        $rows = DB::connection('import')->select('select * from document_types;');
        foreach ($rows as $row) {
            $item = [];
            foreach (get_object_vars($row) as $k => $v) {
                $item[$k] = $v;
            }
            $items[] = $item;
        }

        //insert
        DB::table("document_types")->insert($items);
    }
}
