<?php

namespace MicroPHP\Controller;

class Request
{
    protected $server = [];
    protected $get = [];
    protected $post = [];
    protected $cookie = [];
    protected $request = [];
    protected $files = [];
    protected $env = [];
    protected $session = [];

    public function __construct()
    {
        $this->server = $_SERVER ?? [];
        $this->get = $_GET ?? [];
        $this->post = $_POST ?? [];
        $this->cookie = $_COOKIE ?? [];
        $this->request = $_REQUEST ?? [];
        $this->files = $_FILES ?? [];
        $this->env = $_ENV ?? [];
        $this->session = $_SESSION ?? [];
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

    public function env($key = null)
    {
        $argv = func_get_args();
        return $this->call(__FUNCTION__, ...$argv);
    }

    public function session($key = null)
    {
        session_start();
        $argv = func_get_args();
        return $this->call(__FUNCTION__, ...$argv);
    }

    protected function call($fn, $key = null, $value = null)
    {
        switch (func_num_args()) {
            case 2:
                return $this->$fn[$key] ?? null;
                break;
            case 3:
                $this->$fn[$key] = $value;
                break;
            default:
                return $this->$fn;
        }
    }
}
