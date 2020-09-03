<?php

namespace MicroPHP\Controller;

class Request
{
    protected array $server = [];
    protected array $get = [];
    protected array $post = [];
    protected array $cookie = [];
    protected array $request = [];
    protected array $files = [];

    public function __construct()
    {
        $this->server = $_SERVER;
        $this->get = $_GET;
        $this->post = $_POST;
        $this->cookie = $_COOKIE;
        $this->request = $_REQUEST;
        $this->files = $_FILES;
    }

    public function server($key = null, $value = null)
    {
        $argv = func_get_args();
        return $this->call(__FUNCTION__, ...$argv);
    }

    public function get($key = null, $value = null)
    {
        $argv = func_get_args();
        return $this->call(__FUNCTION__, ...$argv);
    }

    public function post($key = null, $value = null)
    {
        $argv = func_get_args();
        return $this->call(__FUNCTION__, ...$argv);
    }

    public function cookie($key = null, $value = null)
    {
        $argv = func_get_args();
        return $this->call(__FUNCTION__, ...$argv);
    }

    public function request($key = null, $value = null)
    {
        $argv = func_get_args();
        return $this->call(__FUNCTION__, ...$argv);
    }

    public function files($key = null)
    {
        $argv = func_get_args();
        return $this->call(__FUNCTION__, ...$argv);
    }

    protected function call($fn, $key = null, $value = null)
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
