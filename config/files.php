<?php

return [

    'files' => 'files/',

    'audio' => 'audio/',

        'supported_audio' => ['mp3', 'wav', 'wma'],

    'video' => 'video/',

        'supported_video' => [
            'mov',
            'mp4', 'mp4s',
            'mpe', 'mpeg', 'mpg', 'mpg4',
            'flv',
            'avi',
            'm4v', 'm4p',
            'ogv', 'ogx',
            'webm',
            'wmv'
        ],
    
        //http://pastie.org/5668002#237,445,450,452,456,505
        'supported_mimes' => [
            'video/quicktime', //mov
            'video/mp4', 'application/mp4', //mp4, mp4s
            'video/mpeg', 'video/mp4', //mpe, mpeg, mpg, mpg4
            'video/x-flv', //flv
            'video/x-msvideo', //avi
            'video/x-m4v', 'application/mp4', //m4v, m4p
            'video/ogg', 'application/ogg', //ogv ogx
            'video/webm', //webm
            'video/x-ms-wmv' //wmv
        ],

    'images' => 'photos/',

        'supported_images' => ['gif', 'jpg', 'png'],
];
