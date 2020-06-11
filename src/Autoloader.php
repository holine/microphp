<?php

namespace MicroPHP;

use MicroPHP\Library\Singleton;

foreach (glob(__DIR__ . '/Functions/*.php') as $path) {
    require_once($path);
}

spl_autoload_register(function ($class) {
    autoload($class);
});

class Autoloader
{
    public $namespace;

    public function __construct($config = null)
    {
        $this->namespace = Singleton::getInstance('MicroPHP\Library\Namespaces');
    }
}
