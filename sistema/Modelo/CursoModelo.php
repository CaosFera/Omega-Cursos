<?php

namespace sistema\Modelo;
use sistema\Nucleo\Conexao;

class CursoModelo
{
    // Retorna todos os posts com status 1
    public function getAllPost(): array
{
    $query = "SELECT * FROM course WHERE status = 1";
    $stmt = Conexao::getInstancia()->query($query);

    if ($stmt) {
        $resultado = $stmt->fetch_all(MYSQLI_ASSOC);
        
        // Converte os dados binários em base64
        foreach ($resultado as &$course) {
            if (!empty($course['banner'])) {
                $course['banner'] = 'data:image/jpeg;base64,' . base64_encode($course['banner']);
            }
        }

        return $resultado;
    }

    return [];
}


    // Retorna um post específico pelo ID
    public function getPost(int $course_id = null): array
    {
        if (is_null($course_id)) {
            return [];
        }
    
        $query = "SELECT * FROM course WHERE course_id = ? AND status = 1";
        $stmt = Conexao::getInstancia()->prepare($query);
        $stmt->bind_param('i', $course_id);
        $stmt->execute();
    
        $resultado = $stmt->get_result()->fetch_assoc();
    
        if ($resultado && !empty($resultado['banner'])) {
            $resultado['banner'] = 'data:image/jpeg;base64,' . base64_encode($resultado['banner']);
        }
    
        return $resultado ?: [];
    }
    
    
    public function getLogo(): array
    {
        // Consultar o primeiro logo na tabela
        $query = "SELECT * FROM logo ORDER BY logo_id ASC LIMIT 1";
        $stmt = Conexao::getInstancia()->prepare($query); // Usando prepare para manter o padrão
        
        $stmt->execute();
        
        // Obter o resultado
        $resultado = $stmt->get_result()->fetch_assoc();

        // Se houver logo e ele tiver um banner
        if ($resultado && isset($resultado['logo_banner']) && !empty($resultado['logo_banner'])) {
            $imageData = $resultado['logo_banner'];

            // Detectar o tipo MIME
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_buffer($finfo, $imageData);
            finfo_close($finfo);

            // Codificar a imagem para base64
            $resultado['logo_banner'] = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
        }

        // Retornar o logo (ou um array vazio se não encontrado)
        return $resultado ?: [];
    }
    
    public function searchCourse(string $keyword): array
    {
        // Monta a consulta com LIKE para buscar por nome ou descrição
        $query = "SELECT * FROM course WHERE (name LIKE ? OR description LIKE ?) AND status = 1";
        $stmt = Conexao::getInstancia()->prepare($query);

        // Adiciona os curingas para a busca
        $likeKeyword = '%' . $keyword . '%';
        $stmt->bind_param('ss', $likeKeyword, $likeKeyword);

        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
       

        // Converte os dados binários em base64, se houver imagens
        foreach ($resultado as &$course) {
            if (!empty($course['banner'])) {
                $course['banner'] = 'data:image/jpeg;base64,' . base64_encode($course['banner']);
            }
        }

        return $resultado ?: [];
    }

    public function buscarPorId($courseId)
    {
        $sql = "SELECT * FROM course WHERE course_id = ?";
        $stmt = Conexao::getInstancia()->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('i', $courseId);
            $stmt->execute();
            $resultado = $stmt->get_result();
            return $resultado->fetch_assoc(); // Retorna os dados do curso como array associativo
        }
        return null;
    }



}
