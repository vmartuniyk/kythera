<?php
/*
#http://creolab.hr/2013/05/modules-in-laravel-4/
Route::group(array('prefix' => App::getLocale()), function() {

    Route::controller('shop', 'ShopController');
    
    
    //Route::get('shop', function() {
    //    return '<h1>Shop</h1>
    //'; });
    
    
});
*/



//Route::controller(Request::path(), 'ShopController');
Route::controller('/en/page1/shop', 'ShopController');
