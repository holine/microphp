<?php

namespace MicroPHP\Controller;

class Decorator extends Action
{
    public function __construct(Action $handle)
    {
        $this->handle = $handle;
    }

    public function next(): Action
    {
        return $this->handle;
    }

    public function prev(): Action
    {
        $handle = $this->handle;
        while (is_subclass_of($handle, __CLASS__)) {
            $handle = $handle->handle;
        }
        return $handle;
    }

    public function execute(Request $request, Response $response)
    {
        $this->handle->execute($request, $response);
    }
}
