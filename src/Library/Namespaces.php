<?php

namespace MicroPHP\Library;

use function MicroPHP\autoload;

class Namespaces
{
    public function register(String $namespace, String $path)
    {
        $path = realpath($path);
        spl_autoload_register(function ($class) use ($namespace, $path) {
            autoload($class, $namespace, $path);
        });
    }

    public function unregister(String $namespace, String $path)
    {
        $path = realpath($path);
        spl_autoload_unregister(function ($class) use ($namespace, $path) {
            autoload($class, $namespace, $path);
        });
    }
}
