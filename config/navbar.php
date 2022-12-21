<?php

return [
    'menu' => [
        [
            'label' => 'Dashboard',
            'name' => 'home',
            'route' => 'dashboard',
            'icon' => ''
        ],
        [
            'label' => 'Images',
            'name' => 'images',
            'icon' => 'gallery',
            'children' => [
                [
                    'name' => 'new-image',
                    'label' => 'New Image',
                    'icon' => 'add',
                    'route' => 'admin::image.create',
                ],
                [
                    'name' => 'images',
                    'label' => 'Images',
                    'icon' => 'add',
                    'route' => 'admin::image.index',
                ],
            ]
        ]
    ]
];

