<?php

namespace MicroPHP\Controller;

use MicroPHP\Exception\ClassNotFoundException;

abstract class Action
{
    protected array $decorators = [];
    protected Action $handle;

    public function handle(Action $handle)
    {
        $this->handle = $handle;
    }

    public function compile(Action $action = null): Action
    {
        $action ??= $this;
        $decorators = [];
        for (
            $class = get_class($action);
            $vars  = get_class_vars($class);
            $class = get_parent_class($class)
        ) {
            foreach ($vars['decorators'] ?? [] as $decorator => $name) {
                if (is_int($decorator)) {
                    $decorator = $name;
                }
                $decorators[$decorator] ??= $name;
            }
        }
        $decorators = array_reverse($decorators);
        foreach ($decorators as $decorator => $name) {
            $action = $this->compile($this->load($decorator, [$action]));
        }
        return $action;
    }

    protected function load(String $class, array $args = [])
    {
        if (!class_exists($class)) {
            throw new ClassNotFoundException($class);
        }

        if (func_num_args() === 1) {
            return new $class;
        }

        return new $class(...$args);
    }

    abstract public function execute(Request $request, Response $response);
}
