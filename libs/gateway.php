<?php
/**
 * Created by PallyCon.
  * Date: 2017-01-12
 */
require "aes256.php";
require "customException.php";
require "gatewayDTO.php";

define("IV", "0123456789abcdef");


/**
 * Class Gateway
 */
class Gateway
{

    private $cid, $deviceId, $deviceType, $siteKey, $userId;
    private $drmType, $externalKey;
    private $fileName, $filePath;
    private $nonce, $oid;
    private $playbackPolicy, $securityPolicy;
    private $licenseRule, $packInfo, $serviceInfo, $errorInfo;
    private $mpegCencKeyId, $mpegCencKey, $mpegCencIv, $hlsAesKey, $hlsAesIv, $cek;
    private $mpegCenc, $hlsAes, $ncg;
    private $mode, $siteId, $deviceModel, $appVersion, $date, $downloadStatus, $infoOrderId, $infoOne, $infoTwo, $infoThree, $infoFour, $categoryName, $contentName, $lastPlayTime, $lmsPercent, $lmsSections;
    private $encrypter;

    public function __construct($siteKey){
        $this->siteKey = $siteKey;
        $encrypter = new Aes($siteKey, IV);
        $this->encrypter = $encrypter;
    }

    /**
     * 재생관련 룰 설정.
     * @param boolean $limit
     * default = false. 기간제 = true. 무제한 = false, 
     * @param boolean $persistent
     * default = false. offline 시에도 라이센스 유지 여부
     * @param integer $duration
     * license 유지 시간 (단위 : 초). 0 일 경우 무제한.
     * - Reguired
     * limit 값을 true 라면 해당 값 세팅 시 playback_policy.expire_date 를 사용할 수 없습니다.
     *  expire_date 와 duration 중 하나 는 무조건 설정 해야 합니다.
     * @param string $expireDate
     * Playback Expiration Time, GMT Time 표기
     *    ‘yyyy-mm-ddThh:mm:ssZ’
     * - Reguired
     * limit 값을 true 라면 해당 값 세팅 시 playback_policy.duration 를 사용할 수 없습니다.
     * expire_date 와 duration 중 하나 는 무조건 설정 해야 합니다.
     * @return array $playbackPolicy;
     */
    private function createPlaybackPolicy($limit=false, $persistent=false, $duration=0, $expireDate=""){
        /* set plaback_policy */
        $playbackPolicy["limit"] = $this->nvl($limit, false);
        $playbackPolicy["persistent"] = $this->nvl($persistent, false);

        if( $playbackPolicy["limit"] ){
            if(!is_null($duration)){
                $playbackPolicy["duration"] = $this->nvl($duration, 0);

            }else{
                $playbackPolicy["expire_date"] = $this->nvl($expireDate, "");

            }
        }
        $this->playbackPolicy = $playbackPolicy;
        return $playbackPolicy;
    }

    /**
     * 보안 관련 룰 설정
     * @param string $hdcp
     *  '0' : HDCP 보안 미설정. 모든 기기 허용(기본값)<br/>
     *  '1' : HDCP 보안 설정. HDCP 지원 환경만 허용
     * @param boolean $allowExternalDisplay
     * Default = false. NCG only. 모바일 외부 출력 허용 여부.
     * @param boolean $allowMobileAbnormalDevice
     * Default = false. 탈옥 기기 재생 허용 여부
     * @return array
     */
    private function createSecurityPolicy($hdcp, $allowExternalDisplay, $allowMobileAbnormalDevice){
        /* set security_policy.output_protect */
        $outputProtect["allow_external_display"] = $this->nvl($allowExternalDisplay, false);
        $outputProtect["control_hdcp"] = $this->nvl($hdcp, 0);

        /* set security_policy */
        $securityPolicy["output_protect"] =  $outputProtect;
        $securityPolicy["allow_mobile_abnormal_device"] =  $this->nvl($allowMobileAbnormalDevice, false);

        $this->securityPolicy = $securityPolicy;
        return $securityPolicy;
    }

    /**
     * 외부에서 패키징한 컨텐츠에 키 정보를 입력하여 라이센스 요청 시 사용
     * @param string $mpegCencKeyId
     * dash cenc 패키징 시 사용한 key ID 16byte hex string 값
     * @param string $mpegCencKey
     * dash cenc 패키징 시 사용한 key 16byte hex string 값
     * @param string $mpegCencIv
     * dash cenc 패키징 시 사용한 iv 16byte hex string 값
     * @param string $hlsAesKey
     * hls sample aes 패키징 시 사용한 key 16byte hex string 값
     * @param string $hlsAesIv
     * hls sample aes 패키징 시 사용한 iv 16byte hex string 값

     * @return array
    */
    private function createExternalKey($mpegCencKeyId, $mpegCencKey, $mpegCencIv, $hlsAesKey, $hlsAesIv, $cek){
        /* set external_key.mpeg_cenc */
        if(!empty($mpegCencKeyId)){
            $mpegCenc["key_id"] = $mpegCencKeyId;
            $this->mpegCencKeyId = $mpegCencKeyId;
        }
        if(!empty($mpegCencKey))
        {
            $mpegCenc["key"] = $mpegCencKey;
            $this->mpegCencKey = $mpegCencKey;
        }

        if(!empty($mpegCencIv)){
            $mpegCenc["iv"] = $mpegCencIv;
            $this->mpegCencIv = $mpegCencIv;
        }
        /* set external_key.hls_aes */
        if(!empty($hlsAesKey)) {
            $hlsAes["key"] = $hlsAesKey;
            $this->hlsAesKey = $hlsAesKey;
        }
        if(!empty($hlsAesIv)){
            $hlsAes["iv"] = $hlsAesIv;
            $this->hlsAesIv = $hlsAesIv;
        }
        if(!empty($cek)){
            $ncg["cek"] = $cek;
            $this->cek = $cek;
        }

        $externalKey = array();
        /* set external_key */
        if(!empty($mpegCenc)){
            $externalKey["mpeg_cenc"] = $mpegCenc;
            $this->mpegCenc = $mpegCenc;
        }
        if(!empty($hlsAes)){
            $externalKey["hls_aes"] = $hlsAes;
            $this->hlsAes = $hlsAes;
        }
        if(!empty($ncg)){
            $externalKey["ncg"] = $ncg;
            $this->ncg = $ncg;
        }

        $this->externalKey = $externalKey;
        return $externalKey;
    }

    /**
     * @param GatewayDTO $gatewayDTO
     * One time Random String. Response 할 때 동일 값으로 회신해야만 한다
     * license rule 발급을 위한 xml data를 생성하여 aes 암호화  하여 return 한다.
     * @return string $return : aes encrypt json data
     */
    public function createLicenseRule($gatewayDTO){

        $this->createPlaybackPolicy($gatewayDTO->getLimit(), $gatewayDTO->getPersistent(), $gatewayDTO->getDuration(), $gatewayDTO->getExpireDate());
        $this->createSecurityPolicy($gatewayDTO->getControlHdcp(), $gatewayDTO->getAllowExternalDisplay(), $gatewayDTO->getAllowMobileAbnormalDevice());
        $this->createExternalKey($gatewayDTO->getMpegCencKeyId(), $gatewayDTO->getMpegCencKey(), $gatewayDTO->getMpegCencIv(), $gatewayDTO->getHlsAesKey(), $gatewayDTO->getHlsAesIv(), $gatewayDTO->getCek());

        $result["error_code"] = "0000";
        $result["error_message"] = "success";
        $result["playback_policy"] = $this->playbackPolicy;
        $result["security_policy"] = $this->securityPolicy;

        $responseUserId = $gatewayDTO->getResponseUserId();
        if(!empty($responseUserId)){
            $result["response_user_id"] = $gatewayDTO->getResponseUserId();
        }

        if(!empty($this->externalKey)){
            if(!empty($this->mpegCenc)){
                if(empty($this->mpegCencKeyId) xor empty($this->mpegCencKey)){
                    throw new CustomException("mpeg cenc keyId or key empty.","1001");
                }
            }
            if(!empty($this->hlsAes)){
                if(empty($this->hlsAesKey) xor empty($this->hlsAesIv)){
                    throw new CustomException("hls aes key or iv empty.","1002");
                }
            }
            $result["external_key"] = $this->externalKey;
        }
        $result["nonce"] = $gatewayDTO->getNonce();

        $resultJson = json_encode($result);

        $this->licenseRule = $resultJson;
        //echo $resultJson . "<br/>";

        return $this->encrypter->encrypt($resultJson);

    }

    /**
     * @param GatewayDTO $gatewayDTO
     * @return string
     */
    public function createPackageInfo($gatewayDTO){
        $result["error_code"] = "0000";
        $result["error_message"] = "success";
        $result["cid"] = $gatewayDTO->getCid();
        $result["nonce"] = $gatewayDTO->getNonce();

        $resultJson = json_encode($result);
        //echo $resultJson . "<br/>";

        $this->packInfo = $resultJson;

        return $this->encrypter->encrypt($resultJson);
    }

    /**
     * aes 복호화 하여 나온 json string을 parsing 한다.
     * @param string $requestData
     * data parameter 를 통해 전달 받은 값
     * @throws Exception
     * * @return boolean
     */
    public function parseRequestLicense($requestData){
        try{
            $requestData = str_replace(" ","+",$requestData);
            $result = $this->encrypter->decrypt($requestData);

            $jsonArr = json_decode($result, true);

            if($jsonArr){
                $this->userId = $jsonArr["user_id"];
                $this->cid = $jsonArr["cid"];
                $this->oid = $jsonArr["oid"];
                $this->nonce = $jsonArr["nonce"];
                $this->drmType = $jsonArr["drm_type"];

                if(!empty($jsonArr["device_id"])){
                    $this->deviceId = $jsonArr["device_id"];
                }
                if(!empty($jsonArr["device_type"])){
                    $this->deviceType = $jsonArr["device_type"];
                }

                return true;

            }else{
                return false;
            }
        } catch (Exception $e){
            return false;
        }
    }

    /**
     * @param string $requestData
     * @return bool
     */
    public function parseRequestPackage($requestData){
        try{
            $requestData = str_replace(" ","+",$requestData);
            $result = $this->encrypter->decrypt($requestData);
            $jsonArr = json_decode($result, true);

            if($jsonArr){
                $this->fileName = $jsonArr["file_name"];
                $this->filePath = $jsonArr["file_path"];
                $this->nonce = $jsonArr["nonce"];

                return true;

            }else{
                return false;
            }
        } catch (Exception $e){
            return false;
        }

    }

    public function createServiceResult($gatewayDTO)
    {
        // TODO: Implement createServiceResult() method.
        $response = $gatewayDTO->getResponse();
        $message = $gatewayDTO->getMessage();
        $messageTwo = $gatewayDTO->getMessageTwo();

        if(!empty($response)){
            $result["response"] = $gatewayDTO->getResponse();
        }
        if(!empty($message)){
            $result["message"] = $gatewayDTO->getMessage();
        }
        if( !is_null($messageTwo) ){
            $result["message_two"] = $gatewayDTO->getMessageTwo();
        }

        $resultJson = json_encode($result);
        //echo $resultJson . "<br/>";

        $this->serviceInfo = $resultJson;

        return  $this->encrypter->encrypt($resultJson);

    }

    public function parseRequestServiceManager($requestData)
    {
        try{
            $requestData = str_replace(" ","+",$requestData);
            $result = $this->encrypter->decrypt($requestData);

            $jsonArr = json_decode($result, true);
            if($jsonArr){
                $this->mode = $jsonArr["mode"];
                $this->siteId = $jsonArr["site_id"];
                $this->userId = $jsonArr["user_id"];
                $this->deviceId = $jsonArr["device_id"];

                if(!empty($jsonArr["device_model"])) {
                    $this->deviceModel = $jsonArr["device_model"];
                }
                if(!empty($jsonArr["app_version"])) {
                    $this->appVersion = $jsonArr["app_version"];
                }
                if(!empty($jsonArr["date"])) {
                    $this->date = $jsonArr["date"];
                }
                if(!empty($jsonArr["download_status"])) {
                    $this->downloadStatus = $jsonArr["download_status"];
                }
                if(!empty($jsonArr["info_orderID"])) {
                    $this->infoOrderId = $jsonArr["info_orderID"];
                }
                if(!empty($jsonArr["info_one"])) {
                    $this->infoOne = $jsonArr["info_one"];
                }
                if(!empty($jsonArr["info_two"])) {
                    $this->infoTwo = $jsonArr["info_two"];
                }
                if(!empty($jsonArr["info_three"])) {
                    $this->infoThree = $jsonArr["info_three"];

                }
                if(!empty($jsonArr["info_four"])) {
                    $this->infoFour = $jsonArr["info_four"];
                }
                if(!empty($jsonArr["category_name"])) {
                    $this->categoryName = $jsonArr["category_name"];
                }
                if(!empty($jsonArr["content_name"])) {
                    $this->contentName = $jsonArr["content_name"];
                }
                if(!empty($jsonArr["last_play_time"])) {
                    $this->lastPlayTime = $jsonArr["last_play_time"];
                }
                if(!empty($jsonArr["lms_percent"])) {
                    $this->lmsPercent = $jsonArr["lms_percent"];
                }
                if(!empty($jsonArr["lms_sections"])) {
                    $this->lmsSections = $jsonArr["lms_sections"];
                }

            }
        } catch (Exception $e){
            return false;
        }

    }


    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getCid()
    {
        return $this->cid;
    }

    /**
     * @return string
     */
    public function getOid()
    {
        return $this->oid;
    }

    /**
     * @return string
     */
    public function getNonce()
    {
        return $this->nonce;
    }

    /**
     * @return string
     */
    public function getDeviceId()
    {
        return $this->deviceId;
    }

    /**
     * @return string
     */
    public function getDeviceType()
    {
        return $this->deviceType;
    }

    /**
     * @return string
     */
    public function getDrmType()
    {
        return $this->drmType;
    }

    /**
     * @return string
     */
    public function getSiteKey()
    {
        return $this->siteKey;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @return array
     */
    public function getExternalKey()
    {
        return $this->externalKey;
    }

    /**
     * @return array
     */
    public function getPlaybackPolicy()
    {
        return $this->playbackPolicy;
    }

    /**
     * @return array
     */
    public function getSecurityPolicy()
    {
        return $this->securityPolicy;
    }

    /**
     * @return string
     */
    public function getLicenseRule()
    {
        return $this->licenseRule;
    }

    /**
     * @return string
     */
    public function getPackInfo()
    {
        return $this->packInfo;
    }

    /**
     * @return string
     */
    public function getMpegCencKeyId()
    {
        return $this->mpegCencKeyId;
    }

    /**
     * @return string
     */
    public function getMpegCencKey()
    {
        return $this->mpegCencKey;
    }

    /**
     * @return string
     */
    public function getMpegCencIv()
    {
        return $this->mpegCencIv;
    }

    /**
     * @return
     */
    public function getHlsAesKey()
    {
        return $this->hlsAesKey;
    }

    /**
     * @return string
     */
    public function getHlsAesIv()
    {
        return $this->hlsAesIv;
    }

    /**
     * @return string
     */
    public function getCek()
    {
        return $this->cek;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @return string
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * @return string
     */
    public function getDeviceModel()
    {
        return $this->deviceModel;
    }

    /**
     * @return string
     */
    public function getAppVersion()
    {
        return $this->appVersion;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getDownloadStatus()
    {
        return $this->downloadStatus;
    }

    /**
     * @return string
     */
    public function getInfoOrderId()
    {
        return $this->infoOrderId;
    }

    /**
     * @return string
     */
    public function getInfoOne()
    {
        return $this->infoOne;
    }

    /**
     * @return string
     */
    public function getInfoTwo()
    {
        return $this->infoTwo;
    }

    /**
     * @return string
     */
    public function getInfoThree()
    {
        return $this->infoThree;
    }

    /**
     * @return string
     */
    public function getInfoFour()
    {
        return $this->infoFour;
    }

    /**
     * @return string
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    /**
     * @return string
     */
    public function getContentName()
    {
        return $this->contentName;
    }

    /**
     * @return string
     */
    public function getLastPlayTime()
    {
        return $this->lastPlayTime;
    }

    /**
     * @return string
     */
    public function getLmsPercent()
    {
        return $this->lmsPercent;
    }

    /**
     * @return string
     */
    public function getLmsSections()
    {
        return $this->lmsSections;
    }

    /**
     * @return string
     */
    public function getServiceInfo()
    {
        return $this->serviceInfo;
    }

    /**
     * @return string
     */
    public function getErrorInfo()
    {
        return $this->errorInfo;
    }


    /**
     * error code 와 message를 json 형식으로 생성하여 aes 암호화하여 return한다.
     * @param string $errorCode
     * @param string $errorMessage
     * @return string
     */
    public function createErrorTemplete($errorCode, $errorMessage){
        $result = array();
        $result["error_code"] = $errorCode;
        $result["error_message"] = $errorMessage;

        $output = json_encode($result);

        //echo $output;
        $this->errorInfo = $output;
        return  $this->encrypter->encrypt($output);
    }

    /**
     * 입력값이 null 일 경우 다른 값으로 대체한다.
     * @param $original
     * @param $replacement
     * @return mixed
     */
    private function nvl($original, $replacement){
        if(empty($original)){
            return $replacement;
        }else{
            return $original;
        }
    }


}




