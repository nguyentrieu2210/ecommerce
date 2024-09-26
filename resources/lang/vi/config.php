<?php
    return [
        'statusPurchaseOrder' => [
            'pending' => 'status-pending',//đơn nháp
            'pending_processing' => 'status-processing',//chờ nhập
            'partial_processed' => 'status-partial-processing',//nhập 1 phần
            'fully_processed' => 'status-complete',//nhập toàn bộ
            'cancelled' => 'status-cancel'//đã hủy
        ],
        'statusReceiveInventory' => [
            'pending' => 'status-pending',
            'complete' => 'status-complete'
        ],
        'statusPayment' => [
            'pending' => 'status-pending',
            'paid' => 'status-complete'
        ],
        'statusReceive' => [
            'pending'=> 'status-processing',
            'received' =>'status-complete'
        ],
        'promotionMethod' => [
            'promotion_product' => 'Giảm giá sản phẩm',
        ],
        'couponMethod' => [
            'coupon_order' => 'Giảm giá đơn hàng',
            'coupon_ship' => 'Miễn phí vận chuyển'
        ],
        'model' => [
            'product' => 'Sản phẩm',
            'productCatalogue' => 'Danh mục sản phẩm',
            'post' => 'Bài viết',
            'postCatalogue' => 'Danh mục bài viết'
        ],
        'conditionPublish' => ['publish', '=', 2],
        'activePublish' => 2,
        'productCommentPerpage' => 1,
        'newCustomer' => 4,
        'websiteSource' => 8
    ];