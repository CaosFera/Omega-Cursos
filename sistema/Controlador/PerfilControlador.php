<?php

namespace sistema\Controlador;

use sistema\Nucleo\Controlador;
use sistema\Modelo\PerfilModelo;
use sistema\Nucleo\Enrollment;  // Adicionando o modelo Enrollment
use sistema\Nucleo\CursoModelo;  // Supondo que haja um modelo para cursos
use sistema\Nucleo\UsuarioModelo; // Supondo que haja um modelo para usuários

class PerfilControlador extends Controlador
{
    public function __construct()
    {
        parent::__construct('templates/site/views');
    }
    
    // Função para exibir o perfil
    public function perfil(): void
    {
        echo $this->template->renderizar('profile.html', []);
    }

    // Função para matricular um usuário no curso
    public function matricularCurso($courseId): void
    {
        // Verificar se o usuário está logado (verificando se a sessão do usuário existe)
        if (!isset($_SESSION['user_id'])) 
        {
            // Se não estiver logado, redireciona para a página de login
            header('Location: /login');
            exit();
        }

        // Pegar o ID do usuário da sessão
        $userId = $_SESSION['user_id'];

        // Buscar o usuário e o curso
        $usuario = $this->buscarUsuario($userId);
        $curso = $this->buscarCurso($courseId);

        if ($usuario && $curso) 
        {
            // Criar uma instância do modelo de matrícula
            $enrollmentModel = new Enrollment();
            
            // Verificar se o usuário já está matriculado no curso
            if (!$enrollmentModel->isEnrollmentExist($userId, $courseId)) {
                // Matricular o usuário no curso
                if ($enrollmentModel->createEnrollment($userId, $courseId)) {
                    echo "Usuário matriculado com sucesso no curso: " . $curso['name'];
                } else {
                    echo "Erro ao realizar a matrícula. Tente novamente mais tarde.";
                }
            } else {
                echo "Você já está matriculado neste curso.";
            }
        } 
        else 
        {
            echo "Erro ao matricular o usuário. Usuário ou curso não encontrados.";
        }
    }

    // Função para buscar o usuário pelo ID
    private function buscarUsuario($userId)
    {
        // Suponha que exista um modelo de usuário que recupere os dados do banco
        $usuarioModelo = new UsuarioModelo();
        return $usuarioModelo->buscarPorId($userId);
    }

    // Função para buscar o curso pelo ID
    private function buscarCurso($courseId)
    {
        // Suponha que exista um modelo de curso que recupere os dados do banco
        $cursoModelo = new CursoModelo();
        return $cursoModelo->buscarPorId($courseId);
    }
}
?>
