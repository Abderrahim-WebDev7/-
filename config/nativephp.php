<?php

return [
    /*
    |--------------------------------------------------------------------------
    | NativePHP Desktop Configuration
    |--------------------------------------------------------------------------
    */
    
    'name' => 'GAREH - نظام إدارة شركة البيض',
    
    'app_id' => 'com.gareh.app',
    
    'version' => '1.0.0',
    
    'url' => env('APP_URL', 'http://127.0.0.1:8000'),
    
    'windows' => [
        [
            'width' => 1280,
            'height' => 720,
            'min_width' => 800,
            'min_height' => 600,
            'resizable' => true,
            'fullscreen' => false,
            'title' => 'GAREH - نظام إدارة شركة البيض',
            'icon' => resource_path('images/icon.ico'),
        ],
    ],
    
    'php' => [
        'binary_path' => env('PHP_BINARY', 'php'),
    ],
    
    'native_php' => [
        'binary_path' => env('NATIVEPHP_BINARY', null),
    ],
];