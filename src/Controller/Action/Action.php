<?php

namespace MicroPHP\Controller\Action;

use MicroPHP\Controller\Request;
use MicroPHP\Controller\Response;

class Action extends \MicroPHP\Controller\Action
{
    public function execute(Request $request, Response $response)
    {
        $this->handle->compile()->execute($request, $response);
    }
}
