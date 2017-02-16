<?php
class Aes{

    private $key;
    private $iv;
    function __construct($key, $iv)
    {
        $this->key = $key;
        $this->iv = $iv;
    }

    /* ============================================================================== */
    /* =   PAGE : 암호화/복호화 모듈												= */
    /* = -------------------------------------------------------------------------- = */
    /* =   PHP에서 암호화/복호화를 진행하는데 사용되는 모듈입니다. 					= */
	/* =   - 암호화에서 사용하는 함수: encrypt($KEY, $IV, $DATA)					= */
	/* =   - 복호화에서 사용하는 함수: decrypt($KEY, $IV, $DATA)					= */
    /* = -------------------------------------------------------------------------- = */
    /* =   Copyright (c)  2015   INKA Entworks Inc.   All Rights Reserverd.         = */
    /* ============================================================================== */
    private function toPkcs7 ($value)
    {
            if ( is_null ($value) ){
                $value = "" ;
            }
            $padSize = 16 - (strlen ($value) % 16) ;
            return $value . str_repeat (chr ($padSize), $padSize) ;
    }
	/* = -------------------------------------------------------------------------- = */        
    private function fromPkcs7 ($value)
    {
        $valueLen = strlen ($value) ;
        if ( $valueLen % 16 > 0 ){
            $value = "";
        }
        $padSize = ord ($value{$valueLen - 1}) ;
        if ( ($padSize < 1) or ($padSize > 16) ){
            $value = "";
        }
        // Check padding.
        for ($i = 0; $i < $padSize; $i++)
        {
            if ( ord ($value{$valueLen - $i - 1}) != $padSize ){
                $value = "";
            }
        }
        return substr ($value, 0, $valueLen - $padSize) ;
    }
	/* = -------------------------------------------------------------------------- = */
    /* =   암호화/복호화 함수에서 사용되는 함수 END                                 = */
    /* ============================================================================== */


	/* ============================================================================== */
    /* =   암호화/복호화 함수		                                                = */
    /* = -------------------------------------------------------------------------- = */
    /* =   ※ 수정불가															    = */
    /* = -------------------------------------------------------------------------- = */
    /**
    * @param string $value
    * @return string base64 encoding( aes cbc($value) )
    */
    public function encrypt ($value)
    {
        if ( is_null ($value) ){
            $value = "" ;
        }
        $value = $this->toPkcs7 ($value) ;
        $output = mcrypt_encrypt (MCRYPT_RIJNDAEL_128, $this->key, $value, MCRYPT_MODE_CBC, $this->iv) ;
        return base64_encode ($output) ;
    }
	/* = -------------------------------------------------------------------------- = */

    public function decrypt ($value)
    {
        if ( is_null ($value) ){
            $value = "" ;
        }
        $value = base64_decode ($value) ;
        $output = mcrypt_decrypt (MCRYPT_RIJNDAEL_128, $this->key, $value, MCRYPT_MODE_CBC, $this->iv) ;
        return $this->fromPkcs7 ($output) ;
    }
	/* = -------------------------------------------------------------------------- = */
    /* =   암호화/복호화 함수 END			                                        = */
    /* ============================================================================== */

}
