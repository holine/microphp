<?php

namespace MicroPHP;

function client_ip()
{
    $realip = '0.0.0.0';
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($arr as $ip) {
                $ip = trim($ip);
                if ($ip != 'unknown') {
                    $realip = $ip;
                    break;
                }
            }
        } else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $realip = $_SERVER['REMOTE_ADDR'];
        }
    } else if (getenv('HTTP_X_FORWARDED_FOR')) {
        $realip = getenv('HTTP_X_FORWARDED_FOR');
    } else if (getenv('HTTP_CLIENT_IP')) {
        $realip = getenv('HTTP_CLIENT_IP');
    } else {
        $realip = getenv('REMOTE_ADDR');
    }
    return $realip;
}
