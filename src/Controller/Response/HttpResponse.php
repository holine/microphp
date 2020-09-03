<?php

namespace MicroPHP\Controller\Response;

use MicroPHP\Controller\Response;

class HttpResponse extends Response
{
    protected string $directory = '';
    protected string $template = '';
    protected array $assign = [];

    public function directory(string $directory = null): string
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

    public function display(string $template, string $extension = 'phtml')
    {
        $this->template = $template;
        extract($this->assign);
        ob_start();
        include $this->directory  . DIRECTORY_SEPARATOR . $this->template . '.' . $extension;
        $content = ob_get_contents();
        ob_clean();
        $content = str_replace(['  ', "\n", "\r", "\t"], ['', '', '', ''], $content);
        echo $content;
    }
}
