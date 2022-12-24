<?php
namespace App\Plugins\Home;

use Config;
use View;
use DB;

use App\Plugins\Plugin as Plugin;

class HomePlugin extends Plugin
{
    public function content()
    {
        //return "blabla";
        //return view('shop::index');
        
        $rows = DB::connection('import')
        ->select(DB::raw('
                SELECT persons_id as id, email, login as username, password
                FROM users
                LIMIT 1;
                '));
        //echo __FILE__.__LINE__.'<pre>$rows='.htmlentities(print_r($rows,1)).'</pre>';
        
        $version = config('plugin.version');
        //echo __FILE__.__LINE__.'<pre>$version='.htmlentities(print_r($version,1)).'</pre>';
    }
}
