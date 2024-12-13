<?php

namespace sistema\Nucleo;

use sistema\Suporte\Template;

class Controlador
{
    protected Template $template;

    public function __construct(string $diretorio)
    {
        
        if (session_status() == PHP_SESSION_NONE) 
        {
            session_start(); 
        }

        $this->template = new Template($diretorio);
    }
}
