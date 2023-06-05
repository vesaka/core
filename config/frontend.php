<?php

return [
    'staging' => env('STAGING'),
    'game' => [
        'folder' => 'default',
        'versions' => [
            'desktop' => [
                'size' => 300,
                'quality' => 100,
            ],
            'mobile' => [
                'size' => 150,
                'quality' => 65,
            ],
            'tablet' => [
                'size' => 200,
                'quality' => 75,
            ],
        ],
    ],
    'gallery' => [
        'default' => '/src/game/game-6.jpg',
        'formats' => ['image/jpg', 'image/jpeg', 'image/png'],
        'crop' => [
            'x' => 0,
            'y' => 0,
            'width' => 720,
            'height' => 480,
            'rotate' => 0,
            'scaleX' => 1,
            'scaleY' => 1,
        ],
        'conversions' => [
            'small' => [
                'width' => 64,
                'height' => 64,
            ],
            'medium' => [
                'width' => 640,
                'height' => 480,
            ],
            'big' => [
                'width' => 1280,
                'height' => 720,
            ],
            'thumbnail' => [
                'width' => 72,
                'height' => 72,
            ],
        ],
    ],
    'pusher_key' => env('PUSHER_KEY'),
    'pusher' => [
        'key' => env('PUSHER_APP_KEY'),
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'forceTLS' => env('PUSHER_FORCE_TLS'),
    ],
    'locale' => app()->getLocale(),
];
