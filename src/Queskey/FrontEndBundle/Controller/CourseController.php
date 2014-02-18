<?php

namespace Queskey\FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class CourseController extends Controller {
    
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('FrontEndBundle:Course');
        $course = $repository->findOneBy(array('id' => $id));  
        return $this->render('FrontEndBundle:Course:course.html.twig',array('course'=>$course));
                
    }
}

?>
