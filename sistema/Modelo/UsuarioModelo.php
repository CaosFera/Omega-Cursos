<?php

namespace sistema\Modelo;

use sistema\Nucleo\Conexao;

class UsuarioModelo
{
    private $db;

    public function __construct() 
    {
        $this->db = Conexao::getInstancia(); 
    }

    public function cadastrar($dados) 
    {
        // Garantir que o tipo de usuário seja 'user' por padrão
        $user_type = isset($dados['user_type']) ? $dados['user_type'] : 'user';

        // SQL para inserção no banco de dados
        $sql = "INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        if ($stmt) {
            // Gerar o hash da senha
            $passwordHash = password_hash($dados['password'], PASSWORD_BCRYPT);

            // Vincular os parâmetros e executar a consulta
            $stmt->bind_param('ssss', $dados['name'], $dados['email'], $passwordHash, $user_type);
            return $stmt->execute();  // Retorna verdadeiro ou falso dependendo do sucesso
        } else {
            return false;  // Se a preparação da consulta falhar
        }
    }

    public function login($email) 
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);

        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $resultado = $stmt->get_result();
            return $resultado->fetch_assoc();  // Retorna os dados do usuário se encontrados
        } else {
            return null;  // Se a consulta falhar, retorna null
        }
    }
}
