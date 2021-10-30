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
        $this->project = $request->server('REQUEST_PROJECT') ?? '';
        if (empty($this->project)) {
            $aliases = Configure::read('decorator.router.aliases');
            if (!empty($aliases)) {
                foreach ($aliases as $alias => $config) {
                    if (preg_match($alias, $uri, $matches)) {
                        $this->project = $config['project'];
                        $this->controller = $config['controller'];
                        $this->action = $config['action'];
                        if (!empty($config['params'])) {
                            foreach ($config['params'] as $k => $v) {
                                $request->get($v, $matches[$k + 1]);
                            }
                        }
                    }
                }
            }
        }

        $path = explode('/', trim($uri, '/'));

        if (empty($this->project)) {
            $this->project = array_shift($path) ?? Configure::read('decorator.router.default.project');
        }

        if (empty($this->controller)) {
            $this->controller = array_shift($path) ?? Configure::read('decorator.router.default.controller');
        }

        if (empty($this->action)) {
            $this->action = empty($path) ? Configure::read('decorator.router.default.action') : implode('\\', $path);
        }

        $prefix = Configure::read("decorator.router.{$this->project}.prefix")
            ?? Configure::read('decorator.router.default.prefix')
            ?? Configure::read('decorator.router.prefix');
        $shell = PHP_SAPI == 'cli' ? '\\Shell\\' : '\\';
        return "{$prefix}\\{$this->project}\\{$this->controller}{$shell}{$this->action}Action";
    }
    public function execute(Request $request, Response $response)
    {
        $uri = parse_url($request->server('REQUEST_URI'), PHP_URL_PATH);
        if ($action = $this->match(trim($uri, '/'), $request)) {
            $this->prev()->handle($this->load($action));
        }
        $request->server('project', $this->project);
        $request->server('controller', $this->controller);
        $request->server('action', $this->action);

        parent::execute($request, $response);
    }
}
