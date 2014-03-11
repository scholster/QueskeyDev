<?php

namespace Queskey\FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class CourseController extends Controller {
    
    private $loggedInUser;
    
    function __construct()
    {
        $session = new \Symfony\Component\HttpFoundation\Session\Session();
        $session->start();
        $this->loggedInUser = $session->get("User");
        
    }

    public function indexAction($id)
    {
        
        
        if($this->loggedInUser)
        {
            
            $em = $this->getDoctrine()->getManager();
            $insId = $this->checkIfAdmin($id, $em);

        
//            if($this->loggedInUser->getAdmin() && $this->loggedInUser->getId() == $insId[0]['id'])
            if($this->loggedInUser->getId() == $insId[0]['id'])            
            {
                return $this->redirect($this->generateUrl('courseEdit', array('id'=>$id)));
            }
            else
            {
                $array = $this->dbHandle($id, $em);
                return $this->render('FrontEndBundle:Course:course.html.twig',array('course'=>$array['course_info'], 'subscriptionFlag'=>$array['subscriptionFlag']));
            }
        }
        else
        {
            return $this->render('FrontEndBundle:Common:pleaseLogin.html.twig');
        }
    }
    
    
    
    
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $insId = $this->checkIfAdmin($id, $em);
//        if($this->loggedInUser->getAdmin() && $this->loggedInUser->getId() == $insId[0]['id'])
        if($this->loggedInUser)
        {
            if($this->loggedInUser->getId() == $insId[0]['id'])
            {
            
            $array = $this->dbHandle($id, $em);
            return $this->render('FrontEndBundle:Course:courseEdit.html.twig',array('course'=>$array['course_info'], 'subscriptionFlag'=>$array['subscriptionFlag']));
        
            }
        
            else
            {
                return $this->render('FrontEndBundle:Default:notFound.html.twig');
            }
        }
        else
        {
            return $this->render('FrontEndBundle:Common:pleaseLogin.html.twig');
        }
   }
    
    
    
    
    public function dbHandle($id, $em)
    {
         $course_query = $em->createQuery('SELECT c.id, c.name, s.name as subcatname, cat.name as catname, c.description, i.name as ins_name,
                                          p.id as pid, p.price, p.expirytime, p.discountPercent,
                                          p.resubscriptionPrice, p.description
                                          FROM Queskey\FrontEndBundle\Entity\PaymentAssociation pass
                                          JOIN pass.paymentplan p
                                          JOIN pass.course c
                                          JOIN c.subcat s
                                          JOIN s.cat cat
                                          JOIN c.instructor i
                                          WHERE c.id = :id')->setParameter('id', $id);
        
        $array = array();
        $array['course_info'] = $course_query->getResult();;
        $userId = $this->loggedInUser->getId();
        $array['subscriptionFlag'] = $this->checkSubscription($userId, $id, $em);
        
        return $array;
        
    }

    
    
    
    public function checkSubscription($userid, $courseid, $em)
    {
        $course = new \Queskey\FrontEndBundle\Model\CheckSubscription();
        $course_info = $course->checkIfSubscribed($userid, $courseid, $em);
        if($course_info)
        {
            $flag = $course->expiry($course_info);
            return array('expired'=>$flag, 'flag'=>1);
        }
        else
        {
            return array('expired'=>0, 'flag'=>0);
        }
        
    }
    
    public function checkIfAdmin($id, $em)
    {
        $insIdQuery = $em->createQuery('SELECT i.id
                                            FROM Queskey\FrontEndBundle\Entity\Course c
                                            JOIN c.instructor i
                                            WHERE c.id = :id')->setParameter('id', $id);
            
        return $insIdQuery->getResult();
    }
    
}

?>
