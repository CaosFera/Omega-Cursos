<?php

namespace sistema\Nucleo;

class Conexao
{
    private static $instancia;

    public static function getInstancia()
    {
        if (empty(self::$instancia)) {
            try {
                // Conexão com o banco usando MySQLi
                self::$instancia = new \mysqli('localhost', 'root', '', 'omega-cursos', 3306);

                // Verifica se a conexão foi bem-sucedida
                if (self::$instancia->connect_error) {
                    die("Erro de conexão: " . self::$instancia->connect_error);
                }
            } catch (\Exception $ex) {
                die("Erro ao tentar conectar: " . $ex->getMessage());
            }
        }
        return self::$instancia;
    }
}

?>
