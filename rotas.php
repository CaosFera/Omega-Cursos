<?php

use Pecee\SimpleRouter\SimpleRouter;
use sistema\Nucleo\Helpers;

try {
    SimpleRouter::setDefaultNamespace('sistema\Controlador');

    // Página inicial
    SimpleRouter::get(URL_SITE, 'CursoControlador@index'); // Página inicial
    SimpleRouter::get(URL_SITE . 'courses', 'CursoControlador@courses'); // Página de cursos   
    SimpleRouter::get(URL_SITE . 'search', 'CursoControlador@searchCourse');    
    SimpleRouter::get(URL_SITE . 'course/{course_id}', 'CursoControlador@course');

    SimpleRouter::get(URL_SITE . 'login', 'UsuarioControlador@login'); // Renderiza o formulário de login
    SimpleRouter::post(URL_SITE . 'login', 'UsuarioControlador@login'); // Processa o login
    SimpleRouter::get(URL_SITE . 'cadastro', 'UsuarioControlador@cadastro'); // Renderiza o formulário de cadastro
    SimpleRouter::post(URL_SITE . 'cadastro', 'UsuarioControlador@cadastro'); // Processa o cadastro
    SimpleRouter::post(URL_SITE . 'logout', 'UsuarioControlador@logout');

    SimpleRouter::get(URL_SITE . 'perfil', 'PerfilControlador@perfil');
    SimpleRouter::post(URL_SITE . 'matricula', 'PerfilControlador@matricularCurso');

    SimpleRouter::get(URL_SITE . '404', 'CursoControlador@erro404');
    SimpleRouter::start();

} catch (Pecee\SimpleRouter\Exceptions\NotFoundHttpException $ex) 
{
    Helpers::redirecionar('404'); 
}
