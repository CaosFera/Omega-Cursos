<?php

namespace sistema\Modelo;

use sistema\Nucleo\Conexao;

class MatriculaModelo
{
    private $conn;

    public function __construct()
    {
        $this->conn = Conexao::getInstancia();
    }

    // Criação da matrícula
    public function createEnrollment($user_id, $course_id)
    {
        // Verificar se a matrícula já existe
        if ($this->isEnrollmentExist($user_id, $course_id)) {
            return false; // Matrícula já existe
        }

        // SQL para inserir matrícula
        $query = "INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)";

        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("ii", $user_id, $course_id);
            return $stmt->execute(); // Executa a consulta
        }

        return false; // Falha na execução
    }

    // Verifica se a matrícula já existe
    public function isEnrollmentExist($user_id, $course_id)
    {
        $query = "SELECT COUNT(*) FROM enrollments WHERE user_id = ? AND course_id = ?";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("ii", $user_id, $course_id);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            return $count > 0; // Retorna true se já existir matrícula
        }
        return false; // Não existe matrícula
    }

    // Obtém matrícula por ID
    public function getEnrollmentById($enrollment_id)
    {
        $query = "SELECT * FROM enrollments WHERE enrollments_id = ?";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("i", $enrollment_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc(); // Retorna a matrícula
        }
        return null; // Matrícula não encontrada
    }

    // Obtém matrículas pelo ID de usuário
    public function getEnrollmentsByUserId($user_id): array
    {
        $query = "
            SELECT 
                e.*, 
                c.name AS course_name, 
                c.duration, 
                c.created_at, 
                c.banner 
            FROM enrollments e
            INNER JOIN course c ON e.course_id = c.course_id
            WHERE e.user_id = ?
        ";

        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $enrollments = $result->fetch_all(MYSQLI_ASSOC);

            // Processar banners em base64
            foreach ($enrollments as &$enrollment) {
                if (!empty($enrollment['banner'])) {
                    $enrollment['banner'] = 'data:image/jpeg;base64,' . base64_encode($enrollment['banner']);
                }
            }

            return $enrollments;
        }

        return [];
    }

    // Obtém matrículas por ID de curso
    public function getEnrollmentsByCourseId($course_id): array
    {
        $query = "SELECT * FROM enrollments WHERE course_id = ?";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("i", $course_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); // Retorna todas as matrículas do curso
        }
        return []; // Nenhuma matrícula encontrada
    }

    public function cancelEnrollment($user_id, $course_id)
    {
        // Verificar se a matrícula existe
        if (!$this->isEnrollmentExist($user_id, $course_id)) {
            return false; // Matrícula não encontrada
        }

        // SQL para remover a matrícula
        $query = "DELETE FROM enrollments WHERE user_id = ? AND course_id = ?";

        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("ii", $user_id, $course_id);
            return $stmt->execute(); // Executa a consulta para deletar a matrícula
        }

        return false; // Falha na execução da exclusão
    }

}
