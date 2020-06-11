<?php

namespace MicroPHP\Library;

class Configure
{
    protected static $options = array();

    public static function load(array $options)
    {
        static::$options = $options;
    }

    public static function merge(array $from, array $to)
    {
        foreach ($from as $key => $value) {
            if (!isset($to[$key])) {
                $to[$key] = $value;
            } elseif (is_array($from[$key]) && is_array($to[$key])) {
                $to[$key] = static::merge($from[$key], $to[$key]);
            } else {
                $to[$key] = $value;
            }
        }

        return $to;
    }

    public static function read($path, $default = null)
    {
        $result = static::$options;

        $tokens = explode('.', $path);

        foreach ($tokens as $token) {
            if (!is_array($result)) {
                return $default;
            }
            if (!isset($result[$token])) {
                return $default;
            }

            $result = $result[$token];
        }

        return $result;
    }
}
