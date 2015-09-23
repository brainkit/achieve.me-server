<?php
/**
 * Created by PhpStorm.
 * User: Julia
 * Date: 23.09.15
 * Time: 19:12
 */
$fields = array(
    'requestDatetime' => '2011-05-04T20:38:00.000+04:00',
    'action' => 'checkOrder',
    'md5' => '8256D2A032A35709EAF156270C9EFE2E',
    'shopId' =>13,
    'shopArticleId' =>	456,
    'invoiceId' =>	'1234567',
    'customerNumber' =>	'8123294469',
    'orderCreatedDatetime' =>	'2011-05-04T20:38:00.000+04:00',
    'orderSumAmount' => 87.10,
    'orderSumCurrencyPaycash' => '643',
    'orderSumBankPaycash' => '1001'
);
$myCurl = curl_init();
curl_setopt_array($myCurl, array(
    CURLOPT_HEADER =>1,
    CURLOPT_HTTPHEADER => array('Content-type:  application/x-www-form-urlencoded', 'Content-length: 100'),
    CURLOPT_URL => 'http://localhost/achieve.me-server/public/payment/orderCheck',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query(array(/*здесь массив параметров запроса*/))
));
$response = curl_exec($myCurl);
curl_close($myCurl);

echo "Ответ на Ваш запрос: ".$response;