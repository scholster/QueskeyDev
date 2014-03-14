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
        
        $em = $this->getDoctrine()->getManager();
        
        if($this->loggedInUser)
        {
            
            $insId = $this->checkIfAdmin($id, $em);
            
            if($insId)
            {
        
//            if($this->loggedInUser->getAdmin() && $this->loggedInUser->getId() == $insId[0]['id'])
            if($this->loggedInUser->getId() == $insId[0]['id'])            
            {
                return $this->redirect($this->generateUrl('courseAdmin', array('id'=>$id)));
            }
            else
            {
                $array['courseInfo'] = $this->dbHandle($id, $em);
                $array['subscriptionFlag'] = $this->checkSubscription($id, $em);
                
                if($array['courseInfo'])
                {
                                       
                    if($array['subscriptionFlag']['flag'] && !$array['subscriptionFlag']['expired'])
                    {
                        $url = $this->generateUrl('courseContent', array('id'=>$id));
                        $array['courseInfo']['url'] = $url;
                        return $this->render('FrontEndBundle:Course:courseHome.html.twig',array('course'=>$array['courseInfo']));
                    }
                    else
                    {
                        return $this->render('FrontEndBundle:Course:courseDetails.html.twig', array('course'=>$array['courseInfo'], 'subscriptionFlag'=>$array['subscriptionFlag']));
                    }
                }
                else
                {
                return $this->render('FrontEndBundle:Default:notFound.html.twig');
                }
            }
        }
        
        else
        {
            return $this->render('FrontEndBundle:Default:notFound.html.twig');
        }
        }
        
        else
        {
//            return $this->render('FrontEndBundle:Common:pleaseLogin.html.twig');
              $array['courseInfo'] = $this->dbHandle($id, $em);
              $array['subscriptionFlag'] = $this->checkSubscription($id, $em);
              
              if($array['courseInfo'])
              {
                return $this->render('FrontEndBundle:Course:courseDetails.html.twig',array('course'=>$array['courseInfo'], 'subscriptionFlag'=>$array['subscriptionFlag']));
              }
              else
              {
              return $this->render('FrontEndBundle:Default:notFound.html.twig');
              }
        }
}
        
    
    
    
    
    public function adminAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $insId = $this->checkIfAdmin($id, $em);
//        if($this->loggedInUser->getAdmin() && $this->loggedInUser->getId() == $insId[0]['id'])
        if($this->loggedInUser)
        {
            if($this->loggedInUser->getId() == $insId[0]['id'])
            {
            
            $array['courseInfo'] = $this->dbHandle($id, $em);
            if($array['courseInfo'])
            {
                $url = $this->generateUrl('courseContent', array('id'=>$id));
                $array['courseInfo']['url'] = $url;
                return $this->render('FrontEndBundle:Course:courseAdmin.html.twig',array('course'=>$array['courseInfo']));
            }
            else
            {
                return $this->render('FrontEndBundle:Default:notFound.html.twig');
            }
            
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
        
        $result = $course_query->getResult();
        return $result[0];  
    }

    
    
    
    public function checkSubscription($courseid, $em)
    {
        if($this->loggedInUser)
        {
            $userId = $this->loggedInUser->getId();
            $course = new \Queskey\FrontEndBundle\Model\CheckSubscription();
            $course_info = $course->checkIfSubscribed($userId, $courseid, $em);
            if($course_info)
                {
                    $flag = $course->expiry($course_info);
                    return array('expired'=>$flag, 'flag'=>1);       // flag = 1 means user has the subscription.
                }
            else
            {
                return array('expired'=>0, 'flag'=>0);
            }
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
    

    public function contentAction()
    {
        
    }
}

?>
