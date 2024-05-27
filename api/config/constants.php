<?php

return [

    'base_user_name' => env('BASE_USER_NAME', ''),

    'base_user_email' => env('BASE_USER_EMAIL', ''),

    'pagination' => [

        'per_page_options' => [30, 60, 90, 120],

        'per_page_default' => 30,

    ],

    'include_field' => env('INCLUDE_FIELD', 'include'),

];
