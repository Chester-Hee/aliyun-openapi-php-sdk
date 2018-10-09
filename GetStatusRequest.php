<?php

require_once 'vendor/autoload.php';

define('ENABLE_HTTP_PROXY', false);
define('HTTP_PROXY_IP', '127.0.0.1');
define('HTTP_PROXY_PORT', '8888');

$iClientProfile = DefaultProfile::getProfile(
    "cn-hangzhou",  // 默认
    "Your Key",     // 您的Access Key ID
    "Your Secret"   // 您的Access Key Secret
);

$iClientProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", "Cloudauth", "cloudauth.aliyuncs.com");
$client = new DefaultAcsClient($iClientProfile);

// 您在控制台上创建的、采用RPH5BioOnly认证方案的认证场景标识, 创建方法：https://help.aliyun.com/document_detail/59975.html
$biz = "Test";
// 您的认证ID，保持与发起认证接口一致
$ticketId = 'XX';

$getStatusRequest = new \Cloudauth\Request\V20180807\GetStatusRequest();
$getStatusRequest->setBiz($biz);
$getStatusRequest->setTicketId($ticketId);
try {
    $response = $client->getAcsResponse($getStatusRequest);
    $statusCode = $response->Data->StatusCode;
} catch (ServerException $e) {
    print $e->getMessage();
} catch (ClientException $e) {
    print $e->getMessage();
}

// 输出
var_dump($response);