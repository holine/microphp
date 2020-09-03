<?php

namespace MicroPHP\Controller\Decorator;

use MicroPHP\Controller\Decorator;
use MicroPHP\Controller\Request;
use MicroPHP\Controller\Response;
use MicroPHP\Library\Configure;

class Rewrite extends Decorator
{
    public function execute(Request $request, Response $response)
    {
        foreach (Configure::read('decorator.rewrite') as $pattern => $rules) {
            if (preg_match_all($pattern, $request->server('REQUEST_URI'), $match)) {
                $uri = $rules['router'] + Configure::read('decorator.router.default');
                $request->server('REQUEST_URI', "{$uri['controller']}/{$uri['action']}");
                foreach ($rules['rule'] as $key => $param) {
                    $method = $param['method'];
                    $request->$method($param['arg'], $match[$key][0]);
                }
                break;
            }
        }
        parent::execute($request, $response);
    }
}
