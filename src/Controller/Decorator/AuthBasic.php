<?php

namespace MicroPHP\Controller\Decorator;

use MicroPHP\Controller\Decorator;
use MicroPHP\Controller\Request;
use MicroPHP\Controller\Response;

class AuthBasic extends Decorator
{
    public function execute(Request $request, Response $response)
    {
        header('WWW-Authenticate: Basic');
        header('HTTP/1.0 401 Unauthorized');
        parent::execute($request, $response);
    }
}
