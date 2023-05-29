<?php

return [
    'menu' => [
        [
            'label' => 'Dashboard',
            'name' => 'admin::dashboard',
            'route' => 'admin::dashboard',
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
        ],
        [
            'label' => 'Websites',
            'name' => 'websites',
            'icon' => 'website',
            'children' => [
                [
                    'name' => 'new-website',
                    'label' => 'New Website',
                    'icon' => 'add',
                    'route' => 'admin::website.create',
                ],
                [
                    'name' => 'websites',
                    'label' => 'Websites',
                    'icon' => 'add',
                    'route' => 'admin::website.index',
                ],
            ]
        ],
        [
            'label' => 'Skills',
            'name' => 'skills',
            'icon' => 'skill',
            'children' => [
                [
                    'name' => 'new-skill',
                    'label' => 'New Skill',
                    'icon' => 'add',
                    'route' => 'admin::skill.create',
                ],
                [
                    'name' => 'skills',
                    'label' => 'Skills',
                    'icon' => 'add',
                    'route' => 'admin::skill.index',
                ],
            ]
        ]
    ]
];

