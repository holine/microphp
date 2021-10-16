<?php

namespace MicroPHP\Controller\Request;

use MicroPHP\Controller\Request;

class HttpRequest extends Request
{
    protected array $cookie = [];
    protected array $files = [];
    protected array $get = [];
    protected array $post = [];
    protected array $request = [];

    public function __construct()
    {
        $this->cookie = $_COOKIE;
        $this->files = $_FILES;
        $this->get = $_GET;
        $this->post = $_POST;
        $this->request = $_REQUEST;
        parent::__construct();
    }

    public function cookie($key = null, $value = null)
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
}
