<?php

namespace MicroPHP\Model;

class MySQL
{
    static public function dsn(array $config = ['host' => 'localhost', 'database' => 'test', 'port' => 3306]): string
    {
        $config['port'] ??= 3306;
        return "mysql:dbname={$config['database']};host={$config['host']};port={$config['port']};charset=UTF8";
    }
}
