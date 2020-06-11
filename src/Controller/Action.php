<?php

namespace MicroPHP\Controller;

use MicroPHP\Library\Singleton;

abstract class Action
{
    protected $decorators = [];

    abstract public function execute($request, $response);

    public function __construct()
    {
        Singleton::getInstance('MicroPHP\Library\Compile')->init($this);
    }

    final public function dispatch($request, $response)
    {
        Singleton::getInstance('MicroPHP\Library\Compile')->execute($request, $response);
    }
}
