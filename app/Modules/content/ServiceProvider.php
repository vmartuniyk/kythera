<?php
namespace App\Modules\Content;
 
class ServiceProvider extends \App\Modules\ServiceProvider
{
 
    public function register()
    {
        parent::register('content');
    }
 
    public function boot()
    {
        parent::boot('content');
    }
}
