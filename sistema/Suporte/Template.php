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
    // Iniciar a sessão para acessar as variáveis de sessão
    session_start();

    // Verificar se 'user_name' existe na sessão antes de atribuir
    if (isset($_SESSION['user_name'])) {
        $dados['user_name'] = $_SESSION['user_name'];
    } else {
        $dados['user_name'] = 'Usuário não logado';  // Valor padrão caso a chave não exista
    }

    // Adicionar 'user_id' se existir na sessão, caso contrário, null
    $dados['user_id'] = $_SESSION['user_id'] ?? null;

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
