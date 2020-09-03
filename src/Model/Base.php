<?php

namespace MicroPHP\Model;

use MicroPHP\Library\Configure;
use MicroPHP\Model\MySQL;
use PDO;

class Base
{
    const LEFT = 1;
    const RIGHT = 2;
    const INNER = 3;
    const CROSS = 4;
    const FULL = 5;
    protected $handle;
    protected $driver;
    protected $fields = ['*'];
    protected $order = [];
    protected $limit = [];
    protected $where = [];
    protected $join = [];
    protected $on = [];
    protected $config;
    public function __construct()
    {
        $this->driver ??= Configure::read('PDO.driver') ?? 'MySQL';
        $this->config = Configure::read($this->driver);
        $this->driver = 'MicroPHP\\Model\\' . $this->driver;
        $this->handle = new PDO($this->driver::dsn($this->config), $this->config['username'] ?? null, $this->config['password'] ?? null);
    }
    public function query(string $sql)
    {
        return $this->handle->query($sql);
    }
}
