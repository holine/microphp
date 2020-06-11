<?php

namespace MicroPHP\Controller\Response;

use MicroPHP\Controller\Response;

class HttpResponse extends Response
{
    protected $directory = '';
    protected $template = '';
    protected $assign = [];
    protected $extension = '';

    public function directory($directory = null): string
    {
        if (func_num_args()) {
            $this->directory = $directory;
        }
        return $this->directory;
    }

    public function assign($key, $value)
    {
        $this->assign[$key] = $value;
    }

    public function display($template, $extension = 'phtml')
    {
        $this->template = $template;
        $this->extension = $extension;
        unset($template, $extension);
        extract($this->assign);
        ob_start();
        include $this->directory  . DIRECTORY_SEPARATOR . $this->template . '.' . $this->extension;
        $content = ob_get_contents();
        ob_clean();
        $content = str_replace(['  ', "\n", "\r", "\t"], ['', '', '', ''], $content);
        echo $content;
    }
}
