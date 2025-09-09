<?php

return [
    'vnp_TmnCode' => env('VNPAY_TMN_CODE', ''),
    'vnp_HashSecret' => env('VNPAY_HASH_SECRET', ''),
    'vnp_Url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'vnp_Returnurl' => env('VNPAY_RETURN_URL', 'http://127.0.0.1:8000/vnpay/return'),
    'vnp_apiUrl' => env('VNPAY_API_URL', 'http://sandbox.vnpayment.vn/merchant_webapi/merchant.html'),
    'vnp_IpAddr' => env('VNPAY_IP_ADDR', '127.0.0.1'),
    'apiUrl' => env('API_URL', 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction'),
];
