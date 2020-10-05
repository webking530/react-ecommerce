<?php

return [
    'role_structure' => [
        'admin' => [
            'admin'             => 'm',
            'users'             => 'c,r,u,d',
            'products'          => 'c,r,u,d',
            'category'          => 'm',
            'stores'            => 'm',
            'api_credentials'   => 'm',
            'payment_gateway'   => 'm',
            'site_settings'     => 'm',
            'fees'              => 'm',
            'orders'            => 'm',
            'return_policy'     => 'm',
            'reports'           => 'm',
            'country'           => 'm',
            'currency'          => 'm',
            'pages'             => 'm',
            'emails'            => 'm',
            'metas'             => 'm',
            'language'          => 'm',
            'coupon_code'       => 'm',
            'product_reports'   => 'm',
            'block_users'       => 'm',
            'home_page_sliders' => 'm',
            'our_favouritest'   => 'm',
            'join_us'           => 'm',
        ],
        'subadmin' => [
            'admin'             => 'm',
            'users'             => 'c,r',
            'products'          => 'c,r,u',
            'site_settings'     => 'm',
        ],
        'accountant' => [
            'products'          => 'r',
            'fees'              => 'm',
            'orders'            => 'm',
            'reports'           => 'm',
            'product_reports'   => 'm',
        ],
    ],
    'user_roles' => [
        'admin' => [
            ['username' => 'admin', 'email' => 'admin@trioangle.com', 'password' => 'spiffy', 'status' => 'Active', 'created_at' => date('Y-m-d H:i:s')],
        ],
        'subadmin' => [
            ['username' => 'subadmin', 'email' => 'subadmin@trioangle.com', 'password' => 'subadmin123', 'status' => 'Active', 'created_at' => date('Y-m-d H:i:s')],
        ],
        'accountant' => [
            ['username' => 'accountant', 'email' => 'accountant@trioangle.com', 'password' => 'accountant123', 'status' => 'Active', 'created_at' => date('Y-m-d H:i:s')],
        ],
    ],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'view',
        'u' => 'update',
        'd' => 'delete',
        'm' => 'manage',
    ],
];