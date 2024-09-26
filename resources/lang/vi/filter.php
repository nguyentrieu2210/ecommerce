<?php
    return [
        'publish' => [
            0 => 'Chọn tình trạng',
            1 => 'Dừng hoạt động',
            2 => 'Đang hoạt động'
        ],
        'confirmComment' => [
            0 => 'Chọn trạng thái',
            1 => 'Chưa duyệt',
            2 => 'Đã duyệt'
        ],
        'publishSupplier' => [
            1 => 'Dừng hoạt động',
            2 => 'Đang hoạt động'
        ],
        'inventoryStatus' => [
            0 => 'Tất cả',
            1 => 'Còn hàng',
            2 => 'Hết hàng'
        ],
        'follow' => [
            0 => 'Chọn điều hướng',
            1 => 'follow',
            2 => 'nofollow'
        ],
        'paymentMethod' => [
            'cash' => 'Tiền mặt',
            'transfer' => 'Chuyển khoản',
            'card' => 'Thanh toán thẻ'
        ],
        'createdAt' => [
            'none' => 'Ngày tạo',
            'today' => 'Hôm nay',
            'yesterday' => 'Hôm qua',
            '7_days_ago' => '7 ngày qua',
            '30_days_ago' => '30 ngày qua',
            'week' => 'Tuần này',
            'week_ago' => 'Tuần trước',
            'month' => 'Tháng này',
            'month_ago' => 'Tháng trước',
            'year' => 'Năm này',
            'year_ago' => 'Năm trước',
        ],
        'animations' => [
            'slide' => 'Slide',
            'fade' => 'Fade',
            'cube' => 'Cube',
            'coverflow' => 'Coverflow',
            'flip' => 'Flip',
            'cards' => 'Cards',
            'creative' => 'Creative',
            'scroll' => 'Scroll'
        ],
        'moduleWidget' => [
            'product' => 'Sản phẩm',
            'productCatalogue' => 'Nhóm sản phẩm',
            'post' => 'Bài viết',
            'postCatalogue' => 'Nhóm bài viết'
        ],
        'purchaseOrder' => [
            'pending' => 'Đơn nháp',
            'pending_processing' => 'Chờ nhập',
            'partial_processed' => 'Nhập một phần',
            'fully_processed' => 'Nhập toàn bộ',
            'cancelled' => 'Đã hủy'
        ],
        'statusReceive' => [
            'pending'=> 'Chưa nhập kho',
            'received' =>'Đã nhập kho'
        ],
        'statusPayment' => [
            'none' => 'Trạng thái thanh toán',
            'pending' => 'Chưa thanh toán',
            'paid' => 'Đã thanh toán'
        ],
        'statusReceiveInventory' => [
            'pending' => 'Đang giao dịch',
            'complete' => 'Hoàn thành'
        ],
        'statusReceive' => [
            'none' => 'Trạng thái nhập',
            'pending'=> 'Chưa nhập',
            'received' =>'Đã nhập'
        ],
        'statusReturned' => [
            'partially_returned' => 'Trả hàng 1 phần',
            'fully_returned' => 'Trả hàng toàn bộ'
        ],
        'linkMenu' => [
            [
                'keyword' => 'home',
                'name' => 'Trang chủ',
                'htmlTarget' => 'none',
                'nameTarget' => '',
                'canonical' => '',
                'model' => '',
            ],
            [
                'keyword' => 'productCatalogue',
                'name' => 'Danh mục sản phẩm',
                'htmlTarget' => 'select',
                'nameTarget' => 'Chọn danh mục',
                'model' => 'productCatalogue',
                'canonical' => '',
            ],
            [
                'keyword' => 'product',
                'name' => 'Sản phẩm',
                'htmlTarget' => 'select',
                'nameTarget' => 'Chọn sản phẩm',
                'model' => 'product',
                'canonical' => '',
            ],
            [
                'keyword' => 'blog',
                'name' => 'Blog',
                'htmlTarget' => 'select',
                'nameTarget' => 'Chọn Blog',
                'model' => 'postCatalogue',
                'canonical' => '',
            ],
            [
                'keyword' => 'post',
                'name' => 'Bài viết',
                'htmlTarget' => 'select',
                'nameTarget' => 'Chọn bài viết',
                'model' => 'post',
                'canonical' => '',
            ],
            [
                'keyword' => 'addressWebsite',
                'name' => 'Địa chỉ Web',
                'htmlTarget' => 'input',
                'nameTarget' => '',
                'model' => '',
                'canonical' => '',
            ],
        ]
    ];