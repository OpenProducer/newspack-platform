<?php

namespace Facebook\InstantArticles\Client;

use PHPUnit\Framework\TestCase;

class ClientExceptionTest extends TestCase
{
    public function testExtendsException()
    {
        $exception = new ClientException();

        $this->assertInstanceOf('Exception', $exception);
    }
}
