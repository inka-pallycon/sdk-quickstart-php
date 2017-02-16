<?php
require "libs/aes256.php";
require "config.php";
define("IV", "0123456789abcdef");

$requestData	= $_POST["jsonstr"];

$encrypter = new Aes($siteKey, IV);
$responseData = $encrypter->encrypt ($requestData);
echo $responseData;