<?php

namespace MicroPHP\Controller\Decorator;

use MicroPHP\Controller\Decorator;
use MicroPHP\Library\Compile;
use MicroPHP\Library\Configure;
use MicroPHP\Library\Singleton;

class Router extends Decorator
{
    public $project;
    public $controller;
    public $action;
    protected function match($uri, $request)
    {
        $this->project = Configure::read('decorator.router.default.project', '');
        $this->controller = Configure::read('decorator.router.default.controller', '');
        $this->action = Configure::read('decorator.router.default.action', '');
        if (empty($this->project)) {
            $aliases = Configure::read('decorator.router.aliases');
            if (!empty($aliases)) {
                foreach ($aliases as $alias => $config) {
                    if (preg_match_all($alias, $uri, $matches)) {
                        $this->project = $config['project'] ?? '';
                        $uri = ($config['controller'] ?? '') . '/' . ($config['action'] ?? '');
                        foreach ($config['params'] ?? [] as $k => $v) {
                            $method = $v['method'] ?? "get";
                            $request->$method($v['arg'] ?? $v, $matches[$v['arg'] ?? $v][0]);
                        }
                        break;
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

        $path = explode('/', trim($uri, '/'));
        $this->project = empty($path[0]) ? $this->project : $path[0];
        $this->controller = empty($path[1]) ? $this->controller : $path[1];
        $this->action = empty($path[2]) ? $this->action : $path[2];

        $prefix = Configure::read("decorator.router.{$this->project}.prefix") ?? Configure::read('decorator.router.default.prefix') ?? Configure::read('decorator.router.prefix');
        $shell = PHP_SAPI == 'cli' ? '\\Shell\\' : '\\';
        return "{$prefix}\\{$this->project}\\{$this->controller}{$shell}{$this->action}Action";
    }
    public function execute($request, $response)
    {
        $uri = parse_url($request->server('REQUEST_URI'), PHP_URL_PATH);
        $this->project = (string) $request->server('REQUEST_PROJECT');
        if ($action = $this->match($uri, $request)) {
            Singleton::getInstance('MicroPHP\Library\Compile')->init(Compile::load($action));
        }
        $request->server('project', $this->project);
        $request->server('controller', $this->controller);
        $request->server('action', $this->action);
    }
}
