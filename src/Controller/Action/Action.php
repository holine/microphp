<?php

namespace MicroPHP\Controller\Action;

use MicroPHP\Controller\Request;
use MicroPHP\Controller\Response;

class Action extends \MicroPHP\Controller\Action
{
    public function execute(Request $request, Response $response)
    {
        $object = $this->handle->compile();
        while ($handle = get_object_vars($object)['handle'] ?? false) {
            $object = $handle;
        }
        $object->execute($request, $response);
    }
}
