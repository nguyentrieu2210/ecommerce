<?php

return [
    [
        'title' => 'Dashboard',
        'icon' => 'fa fa-th-large',
        'name' => ['dashboard'],
        'submodule' => [
            [
                'title' => 'Dashboard',
                'route' => '/admin/dashboard'
            ]
        ]
    ],
    [
        'title' => 'Thành Viên',
        'icon' => 'fa fa-user',
        'name' => ['userCatalogue', 'user', 'permission'],
        'submodule' => [
            [
                'title' => 'Nhóm Thành Viên',
                'route' => '/admin/userCatalogue'
            ],
            [
                'title' => 'Thành Viên',
                'route' => '/admin/user'
            ],
            [
                'title' => 'Quyền', 
                'route' => '/admin/permission'
            ]
        ]
    ],
    [
        'title' => 'Thuộc Tính',
        'icon' => 'fa fa-sitemap',
        'name' => ['attributeCatalogue' ,'attribute'],
        'submodule' => [
            [
                'title' => 'Nhóm Thuộc Tính',
                'route' => '/admin/attributeCatalogue'
            ],
            [
                'title' => 'Thuộc Tính',
                'route' => '/admin/attribute'
            ]
        ]
    ],
    [
        'title' => 'Sản Phẩm',
        'icon' => 'fa fa-shopping-bag',
        'name' => ['productCatalogue' ,'product'],
        'submodule' => [
            [
                'title' => 'Nhóm Sản Phẩm',
                'route' => '/admin/productCatalogue'
            ],
            [
                'title' => 'Sản Phẩm',
                'route' => '/admin/product'
            ]
        ]
    ],
    [
        'title' => 'Bài Viết',
        'icon' => 'fa fa-file',
        'name' => ['postCatalogue' ,'post'],
        'submodule' => [
            [
                'title' => 'Nhóm Bài Viết',
                'route' => '/admin/postCatalogue'
            ],
            [
                'title' => 'Bài Viết',
                'route' => '/admin/post'
            ]
        ]
    ],
    [
        'title' => 'Khách Hàng',
        'icon' => 'fa fa-users',
        'name' => ['customerCatalogue' ,'customer', 'source'],
        'submodule' => [
            [
                'title' => 'Nhóm Khách Hàng',
                'route' => '/admin/customerCatalogue'
            ],
            [
                'title' => 'Khách Hàng',
                'route' => '/admin/customer'
            ],
            [
                'title' => 'Nguồn Khách',
                'route' => '/admin/source'
            ]
        ]
    ],
    [
        'title' =>  'Quản lý Kho',
        'icon' => 'fa fa-home',
        'name' => ['warehouse', 'inventory', 'supplier', 'purchaseOrder', 'receiveInventory'],
        'submodule' => [
            [
                'title' => 'Chi nhánh',
                'route' => '/admin/warehouse'
            ],
            [
                'title' => 'Tồn kho',
                'route' => '/admin/inventory'
            ],
            [
                'title' => 'Đặt hàng nhập',
                'route' => '/admin/purchaseOrder'
            ],
            [
                'title' => 'Nhập hàng',
                'route' => '/admin/receiveInventory'
            ],
            [
                'title' => 'Nhà cung cấp',
                'route' => '/admin/supplier'
            ],
        ]
    ],
    [
        'title' =>  'Khuyến mại',
        'icon' => 'fa fa-percent',
        'name' => ['coupon', 'promotion'],
        'submodule' => [
            [
                'title' => 'CT khuyến mại',
                'route' => '/admin/promotion'
            ],
            [
                'title' => 'Mã giảm giá',
                'route' => '/admin/coupon'
            ],
        ]
    ],
    [
        'title' =>  'Bình luận',
        'icon' => 'fa fa-comments',
        'name' => ['comment'],
        'submodule' => [
            [
                'title' => 'BL Sản phẩm',
                'route' => '/admin/comment'
            ],
        ]
    ],
    [
        'title' => 'Thiết lập Website',
        'icon' => 'fa fa-cogs',
        'name' => ['system', 'slide', 'menu', 'widget'],
        'submodule' => [
            [
                'title' => 'Cấu hình chung',
                'route' => '/admin/system'
            ],
            [
                'title' => 'Giao diện',
                'route' => '/admin/system/theme'
            ],
            [
                'title' => 'Menu',
                'route' => '/admin/menu'
            ],
            [
                'title' => 'Banner & Slide',
                'route' => '/admin/slide'
            ],
            [
                'title' => 'Widget',
                'route' => '/admin/widget'
            ],
        ]
    ]
];
