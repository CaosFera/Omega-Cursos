<?php

namespace sistema\Controlador;

use sistema\Nucleo\Controlador;
use sistema\Modelo\MatriculaModelo;
use sistema\Modelo\CursoModelo;
use sistema\Modelo\UsuarioModelo;

class MatriculaControlador extends Controlador
{
    public function __construct()
    {
        session_start(); // Garantir que a sessão está iniciada
        parent::__construct('templates/site/views');
    }

    public function isLogin(): ?int
    {
        // Verificar se o usuário está logado
        if (!isset($_SESSION['user_id'])) {
            header('Location: /omega-cursos/login');
            exit(); // Interrompe a execução após o redirecionamento
        }

        // Retornar o ID do usuário logado
        return $_SESSION['user_id'];
    }

    public function matricularCurso($course_id): void
{
    // Verificar se o usuário está logado e obter o ID
    $user_id = $this->isLogin();

    // Validar usuário e curso
    $usuario = $this->buscarUsuario($user_id);
    $curso = $this->buscarCurso($course_id);

    if ($usuario && $curso) {
        // Instanciar o modelo e tentar matricular o usuário
        $matriculaModelo = new MatriculaModelo();
        if (!$matriculaModelo->isEnrollmentExist($user_id, $course_id)) {
            if ($matriculaModelo->createEnrollment($user_id, $course_id)) {
                $_SESSION['message'] = "Matrícula realizada com sucesso!";
                header('Location: /omega-cursos/perfil'); // Redireciona para o perfil após sucesso
                exit();
            } else {
                $_SESSION['message'] = "Erro ao realizar a matrícula. Tente novamente.";
            }
        } else {
            $_SESSION['message'] = "Você já está matriculado neste curso.";
        }
    } else {
        $_SESSION['message'] = "Erro ao matricular o usuário. Usuário ou curso não encontrados.";
    }

    // Redirecionar para a página de cursos caso haja erro
    header('Location: /omega-cursos/courses');
    exit();
}


    private function buscarUsuario($user_id)
    {
        $usuarioModelo = new UsuarioModelo();
        return $usuarioModelo->buscarPorId($user_id);
    }

    private function buscarCurso($course_id)
    {
        $cursoModelo = new CursoModelo();
        return $cursoModelo->buscarPorId($course_id);
    }

    public function perfil(): void
    {
        // Verificar se o usuário está logado e obter o ID
        $user_id = $this->isLogin();

        // Buscar as matrículas do usuário
        $matriculaModelo = new MatriculaModelo();
        $enrollments = $matriculaModelo->getEnrollmentsByUserId($user_id);

        // Buscar os cursos associados a essas matrículas
        $courses = [];
        foreach ($enrollments as $enrollment) {
            $curso = $this->buscarCurso($enrollment['course_id']);
            if ($curso) {
                $courses[] = $curso;
            }
        }

        // Renderizar o template de perfil com os cursos do usuário
        echo $this->template->renderizar('profile.html', ['courses' => $courses]);
    }

    public function cancelarMatricula($course_id)
{
    // Verificar se o usuário está logado e obter o ID
    $user_id = $this->isLogin();

    // Validar matrícula
    $matriculaModelo = new MatriculaModelo();

    // Verificar se a matrícula existe
    if ($matriculaModelo->isEnrollmentExist($user_id, $course_id)) {
        // Realizar o cancelamento da matrícula
        if ($matriculaModelo->cancelEnrollment($user_id, $course_id)) {
            // Redirecionar para a página de perfil após o cancelamento
            header('Location: /omega-cursos/perfil');
            exit();
        } else {
            echo "Erro ao cancelar a matrícula. Tente novamente.";
        }
    } else {
        echo "Você não está matriculado neste curso.";
    }
}



}
