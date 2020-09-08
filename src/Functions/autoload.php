<?php

namespace MicroPHP;

function autoload(String $class, String $namespace = __NAMESPACE__, String $path = null)
{
    $path ??= dirname(__DIR__);
    if (strpos($class, $namespace . '\\') === 0) {
        $path = $path . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, substr($class, strlen($namespace) + 1)) . '.php';
        if (is_readable($path)) {
            require $path;
        }
    }
}
