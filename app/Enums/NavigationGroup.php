<?php

namespace App\Enums;

enum NavigationGroup: string
{
    case USER_MANAGEMENT = 'Quản lý người dùng';
    case PRODUCT_MANAGEMENT = 'Quản lý sản phẩm';
    case ORDER_MANAGEMENT = 'Quản lý đơn hàng';
    case SYSTEM = 'Hệ thống';
}
