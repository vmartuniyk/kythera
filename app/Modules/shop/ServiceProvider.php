<?php
namespace App\Modules\Shop;
 
class ServiceProvider extends \App\Modules\ServiceProvider
{
 
    public function register()
    {
        parent::register('shop');
    }
 
    public function boot()
    {
        parent::boot('shop');
    }
}
