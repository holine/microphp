<?php

namespace MicroPHP\Controller;

abstract class Request
{
    protected array $env = [];

    public function __construct()
    {
        $this->env = $_ENV;
    }

    public function env($key = null, $value = null)
    {
        $argv = func_get_args();
        return $this->call(__FUNCTION__, ...$argv);
    }

    protected function call(string $fn, string $key = null, mixed $value = null)
    {
        switch (func_num_args() - 1) {
            case 1:
                return $this->$fn[$key] ?? null;
                break;
            case 2:
                $this->$fn[$key] = $value;
                break;
            default:
                return $this->$fn;
        }
    }
}
