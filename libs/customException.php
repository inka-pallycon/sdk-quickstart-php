<?php
/**
 * Created by PallyCon.
 * User: hs
 * Date: 2017-01-12
 * Time: 오후 3:12
 */

Class CustomException extends Exception
{
    // 예외를 재정의해서 메시지를 필수값으로 만듭니다
    public function __construct($message, $code = 0, Exception $previous = null) {
        // 처리할 코드

        // 모든 값이 할당되도록 합니다
        parent::__construct($message, $code, $previous);
    }

    // 객체의 사용자 문자열 표현
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}