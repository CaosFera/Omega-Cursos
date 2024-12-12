<?php

namespace sistema\Controlador;

use sistema\Nucleo\Controlador;
use sistema\Modelo\CursoModelo;

class CursoControlador extends Controlador
{
    
    public function __construct()
    {
        parent::__construct('templates/site/views');
    }
    
    public function index():void
    {
        echo $this->template->renderizar('index.html', []);
        
    }

    public function courses():void
    {
        $courses = (new CursoModelo())-> getAllPost();
        echo $this->template->renderizar('courses.html', ['courses' => $courses]);
        
    }

    public function course(int $course_id):void
    {
        $course = (new CursoModelo())-> getPost($course_id);
        echo $this->template->renderizar('course.html', ['course' => $course]);
       
    }   
    
    public function searchCourse(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') 
    {
        $keyword = $_GET['query'] ?? '';  
        if (empty($keyword)) 
        {
            echo $this->template->renderizar('courses.html', ['mensagem' => 'Por favor, insira um termo para busca.']);
            return;
        }
       
        $courses = (new CursoModelo())->searchCourse($keyword);
        echo $this->template->renderizar('search.html', [
            'courses' => $courses,
            'courses_dump' => var_export($courses, true), 
            'mensagem' => empty($courses) ? 'Nenhum curso encontrado.' : ''
        ]);
        
    } 
    else 
    {
        header('Location: /courses');
        exit();
    }
}                                                                                                       
                                                                                                                                                    

   
    
                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
                                                                                                                                                                                                                                                                                                                                                                                                                                                                            public function erro404()
                                                                                                                                                                                                                                                                                                                                                                                                                                                                            {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                echo $this->template->renderizar('404.html', []);

                                                                                                                                                                                                                                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
}
