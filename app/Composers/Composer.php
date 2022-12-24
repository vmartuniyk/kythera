<?php

namespace App\Composers;

class Composer
{

    public function __construct()
    {
    }


    public function compose($view)
    {
        $app = app();
        $view->with('version', $app::VERSION);
    }
}
