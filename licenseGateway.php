<?php
//error_reporting(E_ALL | E_STRICT);
require "libs/gateway.php";
require "config.php";

$gateway = new Gateway($siteKey);

if( isset($_POST["data"]) ){
    //echo $_POST["data"];
    $requestData = $_POST["data"];
}else{
    echo $gateway->createErrorTemplete("2001","Request data is empty.");
    exit();
}
// sample requestData : {"user_id": "test-user", "cid":"DEMOtest-cid", "oid": "", "nonce": "3426u3050329384g", "device_id": "34905esdk-39ru303h-32jd90332", "device_type": "android", "drm_type": "NCG"}
// $encrypter = new Aes($siteKey, IV);
// $requestData = $encrypter->encrypt("{\"user_id\": \"test-user\", \"cid\":\"DEMOtest-cid\", \"oid\": \"\", \"nonce\": \"3426u3050329384g\", \"device_id\": \"34905esdk-39ru303h-32jd90332\", \"device_type\": \"android\", \"drm_type\": \"NCG\"}");

if(!$gateway->parseRequestLicense($requestData)){
    echo $gateway->createErrorTemplete("2002","Fail to parsing the data");
    exit();
}

//echo("userId : " . $gateway->getUserId() . "<br/>");
//echo("cid : " . $gateway->getCid() . "<br/>");
//echo("oid : " . $gateway->getOid() . "<br/>");
//echo("nonce : " . $gateway->getNonce() . "<br/>");
//echo("drmType : " . $gateway->getDrmType() . "<br/>");

/*-
*
* [업체 청책 반영]
*
* 업체의 정책에 맞게 license rule을 생성하는 로직을 이곳에 구현합니다.
*
* ** sample 소스는 무제한 라이센스로 세팅하게 되어 있습니다.
*
*
* [Applying Content Usage Rights rule]
*
* Your Usage Rule generation logic can be applied here.
*
* ** The sample source is setted unlimit license.
*
*
*/

$gatewayDto = new GatewayDTO();
$gatewayDto->setNonce($gateway->getNonce());

$gatewayDto->setLimit(false);
//$gatewayDto->setDuration(6000);
//$gatewayDto->setExpireDate("2017-01-12T12:00:00Z");
//$gatewayDto->setAllowExternalDisplay(true);
//$gatewayDto->setResponseUserId("testUser12312903812039");
//$gatewayDto->setMpegCencKey("9028465683937583");
//$gatewayDto->setMpegCencKeyId("9028465683937583");

if(true){
    $response = $gateway->createLicenseRule($gatewayDto);
    //echo $gateway->getLicenseRule();
}else{
    $response = $gateway->createErrorTemplete("4444", "custom error occurred.");
}
echo $response;



