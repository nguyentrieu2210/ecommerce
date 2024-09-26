<?php
    return [
        'authentication' => function () {
            // Điều hướng xác thực của bạn ở đây
        },
        'backends' => [
            'default' => [
                'adapter' => 'local',
                'baseUrl' => '/ckfinder/userfiles/',
                'root' => public_path('userfiles'), // Thư mục lưu trữ file
            ],
        ],
    ];
    