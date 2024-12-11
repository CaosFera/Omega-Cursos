<?php

namespace sistema\Modelo; 

use sistema\Nucleo\Modelo;



class Perfil 
{
    private $enrollment_id;
    private $user_id;
    private $course_id;

    // Conexão com o banco de dados
    private $conn;

    public function __construct() {
        // Obter a instância da conexão
        $this->conn = Conexao::getInstancia();
    }

    // Função para criar uma matrícula
    public function createEnrollment($user_id, $course_id) {
        // Verificar se a matrícula já existe
        if ($this->isEnrollmentExist($user_id, $course_id)) {
            return false; // Matrícula já existe
        }

        // Preparando a consulta SQL para inserir um novo registro
        $query = "INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)";
        
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("ii", $user_id, $course_id);
            return $stmt->execute();
        } else {
            return false;
        }
    }

    // Função para verificar se a matrícula já existe (única por usuário e curso)
    public function isEnrollmentExist($user_id, $course_id) {
        $query = "SELECT COUNT(*) FROM enrollments WHERE user_id = ? AND course_id = ?";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("ii", $user_id, $course_id);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            return $count > 0;
        }
        return false;
    }

    // Função para obter a matrícula pelo ID
    public function getEnrollmentById($enrollment_id) {
        $query = "SELECT * FROM enrollments WHERE enrollments_id = ?";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("i", $enrollment_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        return null;
    }

    // Função para obter as matrículas de um usuário
    public function getEnrollmentsByUserId($user_id) {
        $query = "SELECT * FROM enrollments WHERE user_id = ?";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    // Função para obter as matrículas de um curso
    public function getEnrollmentsByCourseId($course_id) {
        $query = "SELECT * FROM enrollments WHERE course_id = ?";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("i", $course_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }
}

?>

