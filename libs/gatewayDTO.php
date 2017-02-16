<?php
/**
 * Created by PallyCon.
 * User: hs
 * Date: 2017-01-12
 * Time: 오후 3:12
 */
Class GatewayDTO
{
    private $allowExternalDisplay = false;
    private $allowMobileAbnormalDevice = false;
    private $hardwareDrm = false;
    private $limit = false;
    private $persistent = false;

    private $controlHdcp = "0";
    private $duration;
    private $expireDate;
    private $hlsAesKey;
    private $hlsAesIv;
    private $mpegCencKeyId;
    private $mpegCencKey;
    private $mpegCencIv;
    private $nonce;
    private $cid;
    private $cek;
    private $responseUserId;
    private $response;
    private $message;
    private $message_two;


    /**
     * GatewayDTO constructor.
     */
    public function __construct(){}


    /**
     * @return boolean
     */
    public function getAllowExternalDisplay()
    {
        return $this->allowExternalDisplay;
    }

    /**
     * @param boolean $allowExternalDisplay
     * @throws CustomException
     */
    public function setAllowExternalDisplay($allowExternalDisplay)
    {
        if(!is_bool($allowExternalDisplay)){
            throw new CustomException("allowExternalDisplay type is not boolean", "2001");
        }
        $this->allowExternalDisplay = $allowExternalDisplay;
    }

    /**
     * @return boolean
     */
    public function getAllowMobileAbnormalDevice()
    {
        return $this->allowMobileAbnormalDevice;
    }

    /**
     * @param boolean $allowMobileAbnormalDevice
     * @throws CustomException
     */
    public function setAllowMobileAbnormalDevice($allowMobileAbnormalDevice)
    {
        if(!is_bool($allowMobileAbnormalDevice)){
            throw new CustomException("allowMobileAbnormalDevice type is not boolean", "2001");
        }
        $this->allowMobileAbnormalDevice = $allowMobileAbnormalDevice;
    }

    /**
     * @return boolean
     */
    public function getHardwareDrm()
    {
        return $this->hardwareDrm;
    }

    /**
     * @param boolean $hardwareDrm.
     * @throws CustomException
     */
    public function setHardwareDrm($hardwareDrm)
    {
        if(!is_bool($hardwareDrm)){
            throw new CustomException("hardwareDrm type is not boolean", "2001");
        }
        $this->hardwareDrm = $hardwareDrm;
    }

    /**
     * @return boolean
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param boolean $limit
     * @throws CustomException
     */
    public function setLimit($limit)
    {
        if(!is_bool($limit)){
            throw new CustomException("limit type is not boolean", "2001");
        }
        $this->limit = $limit;
    }

    /**
     * @return boolean
     */
    public function getPersistent()
    {
        return $this->persistent;
    }

    /**
     * @param boolean $persistent
     * @throws CustomException
     */
    public function setPersistent($persistent)
    {
        if(!is_bool($persistent)){
            throw new CustomException("persistent type is not boolean", "2001");
        }
        $this->persistent = $persistent;
    }

    /**
     * @return string
     */
    public function getControlHdcp()
    {
        return $this->controlHdcp;
    }

    /**
     * @param string $controlHdcp
     * @throws CustomException
     */
    public function setControlHdcp($controlHdcp)
    {
        if(!is_int($controlHdcp)){
            throw new CustomException("controlHdcp type is not integer", "2001");
        }
        $this->controlHdcp = $controlHdcp;
    }

    /**
     * @return integer
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param integer $duration
     * @throws CustomException
     */
    public function setDuration($duration)
    {
        if(!is_int($duration)){
            throw new CustomException("duration type is not integer", "2001");
        }
        $this->duration = $duration;
    }

    /**
     * @return string
     */
    public function getExpireDate()
    {
        return $this->expireDate;
    }

    /**
     * @param string $expireDate
     * @throws CustomException
     */
    public function setExpireDate($expireDate)
    {
        if(!is_string($expireDate)){
            throw new CustomException("expireDate type is not string", "2001");
        }
        $this->expireDate = $expireDate;
    }

    /**
     * @return string
     */
    public function getHlsAesKey()
    {
        return $this->hlsAesKey;
    }

    /**
     * @param string $hlsAesKey
     * @throws CustomException
     */
    public function setHlsAesKey($hlsAesKey)
    {
        if(!is_string($hlsAesKey)){
            throw new CustomException("hlsAesKey type is not string", "2001");
        }
        $this->hlsAesKey = $hlsAesKey;
    }

    /**
     * @return string
     */
    public function getHlsAesIv()
    {
        return $this->hlsAesIv;
    }

    /**
     * @param string $hlsAesIv
     * @throws CustomException
     */
    public function setHlsAesIv($hlsAesIv)
    {
        if(!is_string($hlsAesIv)){
            throw new CustomException("hlsAesIv type is not string", "2001");
        }
        $this->hlsAesIv = $hlsAesIv;
    }

    /**
     * @return string
     */
    public function getMpegCencKeyId()
    {
        return $this->mpegCencKeyId;
    }

    /**
     * @param string $mpegCencKeyId
     * @throws CustomException
     */
    public function setMpegCencKeyId($mpegCencKeyId)
    {
        if(!is_string($mpegCencKeyId)){
            throw new CustomException("mpegCencKeyId type is not string", "2001");
        }
        $this->mpegCencKeyId = $mpegCencKeyId;
    }

    /**
     * @return string
     */
    public function getMpegCencKey()
    {
        return $this->mpegCencKey;
    }

    /**
     * @param string $mpegCencKey
     * @throws CustomException
     */
    public function setMpegCencKey($mpegCencKey)
    {
        if(!is_string($mpegCencKey)){
            throw new CustomException("mpegCencKey type is not string", "2001");
        }
        $this->mpegCencKey = $mpegCencKey;
    }

    /**
     * @return string
     */
    public function getMpegCencIv()
    {
        return $this->mpegCencIv;
    }

    /**
     * @param string $mpegCencIv
     * @throws CustomException
     */
    public function setMpegCencIv($mpegCencIv)
    {
        if(!is_string($mpegCencIv)){
            throw new CustomException("mpegCencIv type is not string", "2001");
        }
        $this->mpegCencIv = $mpegCencIv;
    }

    /**
     * @return string
     */
    public function getNonce()
    {
        return $this->nonce;
    }

    /**
     * @param string $nonce
     * @throws CustomException
     */
    public function setNonce($nonce)
    {
        if(!is_string($nonce)){
            throw new CustomException("nonce type is not string", "2001");
        }
        $this->nonce = $nonce;
    }

    /**
     * @return string
     */
    public function getCid()
    {
        return $this->cid;
    }

    /**
     * @param string $cid
     * @throws CustomException
     */
    public function setCid($cid)
    {
        if(!is_string($cid)){
            throw new CustomException("cid type is not string", "2001");
        }
        $this->cid = $cid;
    }

    /**
     * @return string
     */
    public function getCek()
    {
        return $this->cek;
    }

    /**
     * @param string $cek
     * @throws CustomException
     */
    public function setCek($cek)
    {
        if(!is_string($cek)){
            throw new CustomException("cek type is not string", "2001");
        }
        $this->cek = $cek;
    }

    /**
     * @return string
     */
    public function getResponseUserId()
    {
        return $this->responseUserId;
    }

    /**
     * @param string $responseUserId
     * @throws CustomException
     */
    public function setResponseUserId($responseUserId)
    {
        if(!is_string($responseUserId)){
            throw new CustomException("responseUserId type is not string", "2001");
        }
        $this->responseUserId = $responseUserId;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param string $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessageTwo()
    {
        return $this->message_two;
    }

    /**
     * @param string $message_two
     */
    public function setMessageTwo($message_two)
    {
        $this->message_two = $message_two;
    }





}
