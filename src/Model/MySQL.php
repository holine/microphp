<?php

namespace MicroPHP\Model;

class MySQL
{
    static public function dsn(array $config = ['host' => 'localhost', 'database' => 'test', 'port' => 3306, 'unix_socket' => null,]): string
    {
        $config['port'] ??= 3306;
        return isset($config['unix_socket']) ? "mysql:dbname={$config['database']};unix_socket={$config['unix_socket']};charset=UTF8" : "mysql:dbname={$config['database']};host={$config['host']};port={$config['port']};charset=UTF8";
    }
}
