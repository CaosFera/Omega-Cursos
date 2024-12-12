<?php

namespace sistema\Suporte;

use Twig\Lexer;
use sistema\Nucleo\Helpers;

class Template
{

    private \Twig\Environment $twig;

    public function __construct(string $diretorio)
    {
        $loader = new \Twig\Loader\FilesystemLoader($diretorio);
        $this->twig = new \Twig\Environment($loader);

        $lexer = new Lexer($this->twig, array(
            $this->helpers()
        ));
        $this->twig->setLexer($lexer);
    }

    public function renderizar(string $view, array $dados): string
    {
        if (isset($_SESSION['user_name'])) {
            $dados['user_name'] = $_SESSION['user_name'];
        } else {
            $dados['user_name'] = 'Usuário não logado';
        }
    
        $dados['user_id'] = $_SESSION['user_id'] ?? null;
        error_log('Renderizando com user_id: ' . ($dados['user_id'] ?? 'null')); // Adicione esse log
    
        $dados['message'] = $_SESSION['message'] ?? null;
        unset($_SESSION['message']);
    
        return $this->twig->render($view, $dados);
    }
    


    private function helpers(): void
    {
        array(
            $this->twig->addFunction(
                    new \Twig\TwigFunction('url', function (string $url = null) {
                                return Helpers::url($url);
                            })
            ),
            $this->twig->addFunction(
                    new \Twig\TwigFunction('saudacao', function () {
                                return Helpers::saudacao();
                            })
            ),
            $this->twig->addFunction(
                    new \Twig\TwigFunction('resumirTexto', function (string $texto, int $limite) {
                                return Helpers::resumirTexto($texto, $limite);
                            })
            ),
        );
    }

}
