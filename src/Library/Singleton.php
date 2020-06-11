<?php

namespace MicroPHP\Library;

class Singleton
{
    private static $instance = [];
    private function __construct()
    {
    }
    public static function getInstance($class, $params = null)
    {
        $key = md5(json_encode([$class, serialize($params)]));
        return self::$instance[$key] ?? (self::$instance[$key] = func_num_args() === 1 ? Compile::load($class) : Compile::load($class, $params));
    }
}
