<?php

namespace sistema\Controlador;

use sistema\Nucleo\Controlador;
use sistema\Modelo\UsuarioModelo;

class UsuarioControlador extends Controlador
{
    private UsuarioModelo $modelo;

    public function __construct()
    {
        
        parent::__construct('templates/site/views'); 
        $this->modelo = new UsuarioModelo(); 
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $usuario = $this->modelo->login($email);

            if ($usuario && password_verify($password, $usuario['password'])) 
            {
                //session_start();  
                session_regenerate_id(true);    
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['user_name'] = $usuario['name'];
                $_SESSION['user_type'] = $usuario['user_type'];
                error_log('Novo user_id: ' . $_SESSION['user_id']);
                
                header('Location: perfil');
                exit();
            } 
            else 
            {
                echo $this->template->renderizar('login.html', ['mensagem' => 'Credenciais inválidas.']);
            }
        } 
        else 
        {
            echo $this->template->renderizar('login.html', []);
        }
    }

    public function cadastro(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $nome = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $dados = [
                'name' => $nome,
                'email' => $email,
                'password' => $password,
                'user_type' => 'user'  
            ];
            if ($this->modelo->cadastrar($dados)) 
            {
                echo $this->template->renderizar('login.html', ['mensagem' => 'Cadastro realizado com sucesso. Faça login.']);
            } 
            else 
            {
                echo $this->template->renderizar('cadastro.html', ['mensagem' => 'Erro ao cadastrar usuário.']);
            }
            header('Location: /login');
            exit();
        } 
        else 
        {
            echo $this->template->renderizar('cadastro.html', []);
        }
    }

    public function logout(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_unset();
        session_destroy();
    }
    header('Location: login');
    exit();
}

}
