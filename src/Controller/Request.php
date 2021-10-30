<?php

namespace MicroPHP\Controller;

abstract class Request
{
    protected array $cookie = [];
    protected array $env = [];
    protected array $files = [];
    protected array $get = [];
    protected array $parameters = [];
    protected array $post = [];
    protected array $request = [];
    protected array $server = [];

    public function __construct()
    {
        $this->cookie = $_COOKIE;
        $this->env = $_ENV;
        $this->files = $_FILES;
        $this->get = $_GET;
        $this->post = $_POST;
        $this->request = $_REQUEST;
        $this->server = $_SERVER;
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

    public function cookie($key = null, $value = null)
    {
        $argv = func_get_args();
        return $this->call(__FUNCTION__, ...$argv);
    }

    public function env($key = null, $value = null)
    {
        $argv = func_get_args();
        return $this->call(__FUNCTION__, ...$argv);
    }

    public function files($key = null)
    {
        $argv = func_get_args();
        return $this->call(__FUNCTION__, ...$argv);
    }

    public function get($key = null, $value = null)
    {
        $argv = func_get_args();
        return $this->call(__FUNCTION__, ...$argv);
    }

    public function parameters($key = null, $value = null)
    {
        if ($key && array_key_exists($key, $this->parameters) === false) {
            $options = getopt("{$key}::", ["{$key}::"]);
            if ($options !== false) {
                $this->call(__FUNCTION__, $key, $options[$key]);
            }
        }
        $argv = func_get_args();
        return $this->call(__FUNCTION__, ...$argv);
    }

    public function post($key = null, $value = null)
    {
        $argv = func_get_args();
        return $this->call(__FUNCTION__, ...$argv);
    }

    public function request($key = null, $value = null)
    {
        $argv = func_get_args();
        return $this->call(__FUNCTION__, ...$argv);
    }

    public function server($key = null, $value = null)
    {
        $argv = func_get_args();
        return $this->call(__FUNCTION__, ...$argv);
    }
}
