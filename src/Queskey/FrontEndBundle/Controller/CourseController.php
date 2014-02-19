<?php

namespace Queskey\FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class CourseController extends Controller {
    
    public function indexAction($id)
    {
        $session = new \Symfony\Component\HttpFoundation\Session\Session();
        $session->start();
        $loggedInUser = $session->get("User");
        
        if($loggedInUser)
        {
        $em = $this->getDoctrine()->getManager();
        $course_query = $em->createQuery('SELECT c.id, c.name, c.description, i.name as ins_name,
                                          p.id as pid, p.price, p.expirytime, p.discountPercent,
                                          p.resubscriptionPrice, p.description
                                          FROM Queskey\FrontEndBundle\Entity\PaymentAssociation pass
                                          JOIN pass.paymentplan p
                                          JOIN pass.course c
                                          JOIN c.instructor i
                                          WHERE c.id = :id')->setParameter('id', $id);
        
        $course_info = $course_query->getResult();

        return $this->render('FrontEndBundle:Course:course.html.twig',array('course'=>$course_info));
        }
        
        else
        {
            return $this->render('FrontEndBundle:Common:pleaseLogin.html.twig');
        }
    }
}

?>
