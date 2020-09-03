<?php

namespace MicroPHP;

spl_autoload_register(fn ($class) => autoload($class));

function autoload(String $class, String $namespace = __NAMESPACE__, String $path = __DIR__)
{
    if (strpos($class, $namespace . '\\') === 0) {
        $path = $path . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, substr($class, strlen($namespace) + 1)) . '.php';
        if (is_readable($path)) {
            require $path;
        }
    }
}

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
