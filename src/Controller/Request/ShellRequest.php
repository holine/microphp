<?php

namespace MicroPHP\Controller\Request;

use MicroPHP\Controller\Request;

class ShellRequest extends Request
{
    protected array parameters = [];

    public function parameters($key = null, $value = null)
    {
        if($key && array_key_exists($key, $this->parameters) === false){
            $options = getopt("{$key}::", ["{$key}::"]);
            if($options !== false){
                $this->call(__FUNCTION__, $key, $options[$key]);
            }
        }
        $argv = func_get_args();
        return $this->call(__FUNCTION__, ...$argv);
    }
}
