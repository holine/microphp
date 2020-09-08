<?php

namespace MicroPHP;

foreach (glob(__DIR__ . '/Functions/*.php') as $file) {
    include $file;
}

spl_autoload_register(fn ($class) => autoload($class));

class Autoloader
{
    public $namespace = null;
    public function __construct($config = null)
    {
    }
    public function run($var = null)
    {
    }
    public function namespace(): Library\Namespaces
    {
        $this->namespace ??= new Library\Namespaces();
        return $this->namespace;
    }
}
