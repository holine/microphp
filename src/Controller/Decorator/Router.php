<?php

namespace MicroPHP\Controller\Decorator;

use MicroPHP\Controller\Decorator;
use MicroPHP\Controller\Request;
use MicroPHP\Controller\Response;
use MicroPHP\Library\Configure;

class Router extends Decorator
{
    public string $project;
    public string $controller;
    public string $action;
    protected function match($uri, $request)
    {
        $this->project =  Configure::read('decorator.router.default.project', '');
        $this->controller = Configure::read('decorator.router.default.controller', '');
        $this->action = Configure::read('decorator.router.default.action', '');
        if (empty($this->project)) {
            $aliases = Configure::read('decorator.router.aliases');
            if (!empty($aliases)) {
                foreach ($aliases as $alias => $config) {
                    if (preg_match($alias, $uri, $matches)) {
                        $this->project = $config['project'];
                        $uri = $config['controller'] . '/' . $config['action'];
                        if (!empty($config['params'])) {
                            foreach ($config['params'] as $k => $v) {
                                $request->get($v, $matches[$k + 1]);
                            }
                        }
                    }
                }
            }

            if (empty($this->project)) {
                if (strpos($uri, '/')) {
                    $this->project = substr($uri, 0, strpos($uri, '/'));
                    $uri = substr($uri, strpos($uri, '/') + 1);
                } else {
                    $this->project = $uri;
                    $uri = "";
                }
            }
        }

        $path = explode('/', ltrim($uri, '/'));

        $this->controller = empty($path[0]) ? $this->controller : $path[0];
        $this->action = empty($path[1]) ? $this->action : $path[1];

        $prefix = Configure::read("decorator.router.{$this->project}.prefix")
            ?? Configure::read('decorator.router.default.prefix')
            ?? Configure::read('decorator.router.prefix');
        $shell = PHP_SAPI == 'cli' ? '\\Shell\\' : '\\';
        return "{$prefix}\\{$this->project}\\{$this->controller}{$shell}{$this->action}Action";
    }
    public function execute(Request $request, Response $response)
    {
        $uri = parse_url($request->server('REQUEST_URI'), PHP_URL_PATH);
        $this->project = (string) $request->server('REQUEST_PROJECT');
        if ($action = $this->match($uri, $request)) {
            $this->prev()->handle($this->load($action));
        }
        $request->server('project', $this->project);
        $request->server('controller', $this->controller);
        $request->server('action', $this->action);

        parent::execute($request, $response);
    }
}
