<?php

namespace MicroPHP\Library;

use MicroPHP\Exception\ClassNotFoundException;

class Compile
{
    protected $decorators = [];
    protected $actions = [];
    protected $class = [];
    protected $index = 0;

    public function init($action)
    {
        $class = get_class($action);
        if (in_array($class, $this->class)) {
            return true;
        }
        $this->index ? array_splice($this->actions, $this->index, 0, [$action]) : array_unshift($this->actions, $action);
        array_push($this->class, $class);
        while ($class) {
            $decorators = new \ReflectionProperty($class, 'decorators');
            $decorators->setAccessible(true);
            foreach (array_reverse($decorators->getValue($action) ?? []) as $decorator) {
                if (in_array($decorator, $this->class)) {
                    continue;
                }
                if ($this->index) {
                    array_splice($this->decorators, 1, 0, $decorator);
                } else {
                    array_unshift($this->decorators, $decorator);
                }
                array_push($this->class, $decorator);
            }
            if ($class = get_parent_class($class)) {
                if ((new \ReflectionClass($class))->isAbstract()) {
                    break;
                }
                $action = self::load($class);
            }
        }
    }

    public function execute($request, $response)
    {
        while ($decorator = array_shift($this->decorators)) {
            $this->index = count($this->actions);
            self::load($decorator)->execute($request, $response);
        }
        while ($action = array_shift($this->actions)) {
            $action->execute($request, $response);
        }
    }

    public static function load($class, $params = null)
    {
        if (!class_exists($class)) {
            throw new ClassNotFoundException($class);
        }
        return func_num_args() === 1 ? new $class : new $class(...$params);
    }
}
