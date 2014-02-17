<?php

namespace Queskey\FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class CourseController extends Controller {
    
    public function indexAction()
    {
        $request = $this->get('request');
        $id = $request->get('id');
        return $this->render('FrontEndBundle:Course:course.html.twig',array("id"=>$id));
                
    }
}

?>
