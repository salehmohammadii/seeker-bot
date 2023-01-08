<?php
$MerchantID = 'e69f24ee-efa6-11e7-982a-000c295eb8fc'; //Required
$Amount = 15000; //Amount will be based on Toman - Required
$Description = 'توضیحات تراکنش تستی'; // Required
$Email = 'UserEmail@Mail.Com'; // Optional
$Mobile = '09123456789'; // Optional
$CallbackURL = 'http://www.mychanneladminbot.ir/verify.php'; // Required


$client = new SoapClient('https://sandbox.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

$result = $client->PaymentRequest(
    [
        'MerchantID' => $MerchantID,
        'Amount' => $Amount,
        'Description' => $Description,
        'Email' => $Email,
        'Mobile' => $Mobile,
        'CallbackURL' => $CallbackURL,
    ]
);

//Redirect to URL You can do it also by creating a form
if ($result->Status == 100) {
    return "Location: https://sandbox.zarinpal.com/pg/StartPay/".$result->Authority;
} else {
    echo'ERR: '.$result->Status;
}