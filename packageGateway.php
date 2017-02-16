<?php
error_reporting(E_ERROR|E_WARNING|E_PARSE);
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
$gateway = new Gateway($siteKey);

// sample requestData : {"file_name":"test-content.mp4", "file_path":"c:\\lecture1\\test-content.mp4", "nonce":"483476569283"}
// $encrypter = new Aes($siteKey, IV);
// $requestData = $encrypter->enc("{"file_name":"test-content.mp4", "file_path":"c:\\lecture1\\test-content.mp4", "nonce":"483476569283"}");

if(!$gateway->parseRequestPackage($requestData)){
    echo $gateway->createErrorTemplete("2002","Fail to parsing the data");
    exit();
}

//echo("fileName : " . $gateway->getFileName() . "<br/>");
//echo("filePath : " . $gateway->getFilePath() . "<br/>");

/*-
*
* [업체 청책 반영]
*
* 업체의 정책에 맞게 Content ID를 생성하는 로직을 이곳에 구현합니다.
* Content ID를 생성하는데 활용할 값은 다음과 같습니다.
*
* - $gateway->getFileName()
* - $gateway->getFilePath()
*
* ** sample 소스는 무조건 CID를 test-sample 으로 설정됩니다.
*
*
* [Applying CID rule]
*
* Your CID generation logic can be applied here.
* The below parameters can be used for the logic.
*
* - $gateway->getFileName()
* - $gateway->getFilePath()
*
* ** The default $ContentID value for test is test-sample.
*
*/
$gatewayDto = new GatewayDTO();
$gatewayDto->setNonce($gateway->getNonce());

$gatewayDto->setCid("test-sample");

if(true){
    $response = $gateway->createPackageInfo($gatewayDto);

}else{
    $response = $gateway->createErrorTemplete("4444", "custom error occurred.");
}
echo $response;



