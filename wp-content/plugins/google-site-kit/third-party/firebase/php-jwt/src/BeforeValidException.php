<?php

namespace Google\Site_Kit_Dependencies\Firebase\JWT;

class BeforeValidException extends \UnexpectedValueException implements \Google\Site_Kit_Dependencies\Firebase\JWT\JWTExceptionWithPayloadInterface
{
    private object $payload;
    public function setPayload(object $payload) : void
    {
        $this->payload = $payload;
    }
    public function getPayload() : object
    {
        return $this->payload;
    }
}
