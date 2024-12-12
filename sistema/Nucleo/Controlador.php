<?php

namespace sistema\Nucleo;

use sistema\Suporte\Template;

class Controlador
{
    protected Template $template;

    public function __construct(string $diretorio)
    {
        // Iniciar a sessão aqui, garantindo que a sessão seja inicializada
        if (session_status() == PHP_SESSION_NONE) 
        {
            session_start(); // Verifica se a sessão ainda não foi iniciada
        }

        // Cria o objeto Template
        $this->template = new Template($diretorio);
    }
}
