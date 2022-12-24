<?php

use Kythera\Support\ViewEntity;

return array(

    /*
    |--------------------------------------------------------------------------
    | Name of route
    |--------------------------------------------------------------------------
    |
    | Enter the routes name to enable dynamic imagecache manipulation.
    | This handle will define the first part of the URI:
    |
    | {route}/{template}/{filename}
    |
    | Examples: "images", "img/cache"
    |
    */

    //kfn.laravel.debian.mirror.virtec.org/en/media/small/1/1057695278.jpg
    //kfn.laravel.debian.mirror.virtec.org/en/media/small/img/map_kythera25ENG.jpg
    'route' => "en/media",
    //'route' => null,

    /*
    |--------------------------------------------------------------------------
    | Storage paths
    |--------------------------------------------------------------------------
    |
    | The following paths will be searched for the image filename, submitted
    | by URI.
    |
    | Define as many directories as you like.
    |
    */

    'paths' => [
        public_path(),
        public_path('assets'),
        public_path('photos'),
        public_path('videos'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Manipulation templates
    |--------------------------------------------------------------------------
    |
    | Here you may specify your own manipulation filter templates.
    | The keys of this array will define which templates
    | are available in the URI:
    |
    | {route}/{template}/{filename}
    |
    | The values of this array will define which filter class
    | will be applied, by its fully qualified name.
    |
    */

    'templates' => [

        'small' => function ($image) {
            return $image->fit(120, 90);
        },
        'medium' => function ($image) {
            return $image->fit(194, 145);
        },
        'large' => function ($image) {
            return $image->fit(480, 360);
        },
        'normal' => function ($image) {
            return $image;
        },
        'sidebar' => function ($image) {
            $width  = ViewEntity::SIDEBAR_WIDTH;
            $height = null;
            return $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        },
        'small' => function ($image) {
            $width  = ViewEntity::SIDEBAR_WIDTH;
            $height = null;
            return $image->fit($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        },
        'bigkey' => function ($image) {
            $width  = ViewEntity::BIG_KEYS_WIDTH;
            $height = ViewEntity::BIG_KEYS_HEIGHT;
            return $image->fit($width, $height, null, 'top');
        },
        'key' => function ($image) {
            $width  = ViewEntity::KEYS_WIDTH;
            $height = ViewEntity::KEYS_HEIGHT;
            return $image->fit($width, $height, null, 'top');
        },
        'footer' => function ($image) {
            $width  = ViewEntity::VIEW_MODE_FOOTER_WIDTH;
            $height = ViewEntity::VIEW_MODE_FOOTER_HEIGHT;
            return $image->fit($width, $height, null, 'center');
        },
        'homerow' => function ($image) {
            $width  = ViewEntity::HOMEROW_KEYS_WIDTH;
            $height = ViewEntity::HOMEROW_KEYS_HEIGHT;
            return $image->fit($width, $height, null, 'top');
        },
        'view' => function ($image) {
            $width  = ViewEntity::VIEW_MODE_VIEW_WIDTH;
            $height = null;
            return $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        },
        'list' => function ($image) {
            $width  = ViewEntity::VIEW_MODE_LIST_WIDTH;
            $height = ViewEntity::VIEW_MODE_LIST_HEIGHT;
            return $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        },
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Cache Lifetime
    |--------------------------------------------------------------------------
    |
    | Lifetime in minutes of the images handled by the imagecache route.
    |
    */

    'lifetime' => (60*24*30), //30 days

);
