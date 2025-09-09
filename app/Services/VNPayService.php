<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class VNPayService
{
    protected $tmnCode;
    protected $hashSecret;
    protected $url;
    protected $returnUrl;
    protected $vnp_apiUrl;

    protected $apiUrl;
    protected $ipAddr;

    public function __construct()
    {
        $this->tmnCode = config('vnpay.vnp_TmnCode');
        $this->hashSecret = config('vnpay.vnp_HashSecret');
        $this->url = config('vnpay.vnp_Url');
        $this->returnUrl = config('vnpay.vnp_Returnurl');
        $this->vnp_apiUrl = config('vnpay.vnp_apiUrl');
        $this->ipAddr = config('vnpay.vnp_IpAddr');
        $this->apiUrl = config('vnpay.apiUrl');
    }

    /**
     * Tạo URL thanh toán VNPay
     */
    public function createPaymentUrl($orderData)
    {
        $vnp_Url = $this->url;
        $vnp_Returnurl = $this->returnUrl;
        $vnp_TmnCode = $this->tmnCode;
        $vnp_HashSecret = $this->hashSecret;

        $vnp_TxnRef = $orderData['order_number'];
        $vnp_OrderInfo = $orderData['order_info'] ?? 'Thanh toan don hang ' . $orderData['order_number'];
        $vnp_OrderType = 'other';
        $vnp_Amount = $orderData['amount']; // Số tiền gốc
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'VNPAY';
        $vnp_IpAddr = $this->ipAddr;
        $startTime = now()->addHours(7)->format('YmdHis');
        $expire = now()->addHours(7)->addMinutes(15)->format('YmdHis');

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount * 100, // VNPay yêu cầu số tiền nhân với 100
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => "Thanh toan GD:" . $vnp_TxnRef,
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate"=>$expire
        );

        

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }


        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return $vnp_Url;
    }

    /**
     * Xác thực callback từ VNPay
     */
    public function validateCallback($data)
    {
        $vnp_SecureHash = $data['vnp_SecureHash'];
        unset($data['vnp_SecureHash']);
        unset($data['vnp_SecureHashType']);

        ksort($data);
        $i = 0;
        $hashData = "";
        foreach ($data as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $vnpSecureHash = hash_hmac('sha512', $hashData, $this->hashSecret);

        return $vnpSecureHash === $vnp_SecureHash;
    }

    /**
     * Kiểm tra trạng thái giao dịch
     */
    public function checkTransactionStatus($vnpTxnRef)
    {
        $vnp_RequestId = rand(1, 10000);
        $vnp_Version = "2.1.0";
        $vnp_Command = "querydr";
        $vnp_TmnCode = $this->tmnCode;
        $vnp_TxnRef = $vnpTxnRef;
        $vnp_OrderInfo = "Kiem tra giao dich";
        $vnp_TransactionDate = date('YmdHis');
        $vnp_CreateDate = date('YmdHis');
        $vnp_IpAddr = request()->ip();

        $inputData = array(
            "vnp_RequestId" => $vnp_RequestId,
            "vnp_Version" => $vnp_Version,
            "vnp_Command" => $vnp_Command,
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_TransactionDate" => $vnp_TransactionDate,
            "vnp_CreateDate" => $vnp_CreateDate,
            "vnp_IpAddr" => $vnp_IpAddr,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $this->vnp_apiUrl . "?" . $query;
        if (isset($this->hashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->hashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $vnp_Url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
