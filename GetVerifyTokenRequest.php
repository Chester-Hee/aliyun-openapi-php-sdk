<?php

require_once 'vendor/autoload.php';

define('ENABLE_HTTP_PROXY', false);
define('HTTP_PROXY_IP', '127.0.0.1');
define('HTTP_PROXY_PORT', '8888');

function guid()
{
    mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
    $char_id = strtoupper(md5(uniqid(rand(), true)));
    $hyphen = chr(45);// "-"
    $uuid = chr(123)// "{"
        . substr($char_id, 0, 8) . $hyphen
        . substr($char_id, 8, 4) . $hyphen
        . substr($char_id, 12, 4) . $hyphen
        . substr($char_id, 16, 4) . $hyphen
        . substr($char_id, 20, 12)
        . chr(125);// "}"
    return $uuid;
}

$iClientProfile = DefaultProfile::getProfile(
    "cn-hangzhou",  // 默认
    "Your Key",     // 您的Access Key ID
    "Your Secret"   // 您的Access Key Secret
);

$iClientProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", "Cloudauth", "cloudauth.aliyuncs.com");
$client = new DefaultAcsClient($iClientProfile);

$biz = "Test"; //您在控制台上创建的、采用RPH5BioOnly认证方案的认证场景标识, 创建方法：https://help.aliyun.com/document_detail/59975.html
$ticketId = guid();  //认证ID, 由使用方指定, 发起不同的认证任务需要更换不同的认证ID
var_dump($ticketId);

$getVerifyTokenRequest = new  \Cloudauth\Request\V20180807\GetVerifyTokenRequest();
$getVerifyTokenRequest->setBiz($biz);
$getVerifyTokenRequest->setTicketId($ticketId);
//若需要binding图片(如身份证正反面等), 且使用base64上传, 需要设置请求方法为POST
//$getVerifyTokenRequest->setMethod("POST");
$getVerifyTokenRequest->setBinding("{\"Name\": \"张三\",\"IdentificationNumber\": \"343322199309222322\"}");

try {
    $response = $client->getAcsResponse($getVerifyTokenRequest);
    $token = $response->Data->VerifyToken->Token; //token默认30分钟时效，每次发起认证时都必须实时获取
} catch (Exception $e) {
    echo json_encode($e->getMessage());
}

// 这里输出得到一个跳转链接
var_dump($response);