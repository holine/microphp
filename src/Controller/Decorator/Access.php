<?php

namespace MicroPHP\Controller\Decorator;

use MicroPHP\Controller\Decorator;
use MicroPHP\Controller\Request;
use MicroPHP\Controller\Response;
use MicroPHP\Model\Base;

use function MicroPHP\client_ip;

class Access extends Decorator
{
    public function execute(Request $request, Response $response)
    {
        $data = [
            'timestamp' => time(),
            'client' => ip2long(client_ip()),
            'path' => $_SERVER['REQUEST_URI'],
            'referer' => $_SERVER['HTTP_REFERER'] ?? '',
            'agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'spider' => 0,
        ];
        $column = implode('`,`', array_keys($data));
        $marker = implode(',', array_fill(0, count($data), '?'));
        $sth = (new Base())->prepare("INSERT INTO `asscss` (`{$column}`) values ({$marker})");
        $sth->execute($data);
        parent::execute($request, $response);
    }
}
