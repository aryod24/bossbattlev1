<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Question Bank Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains metadata for each question bank including name,
    | icon, and description. Bank groups are identified by numeric IDs.
    |
    */

    'banks' => [
        1 => [
            'name' => 'PHP Basics',
            'icon' => 'code',
            'description' => 'Fundamental PHP concepts, syntax, and basic operations',
        ],
        2 => [
            'name' => 'PHP Advanced',
            'icon' => 'code_blocks',
            'description' => 'OOP, Laravel framework, and advanced PHP topics',
        ],
        3 => [
            'name' => 'JavaScript',
            'icon' => 'javascript',
            'description' => 'ES6+, DOM manipulation, and async programming',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Bank Settings
    |--------------------------------------------------------------------------
    */

    'default_icon' => 'quiz',
    'default_xp' => [
        'Easy' => 10,
        'Medium' => 15,
        'Hard' => 20,
    ],
];
