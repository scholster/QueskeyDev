<?php

namespace Queskey\FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    private $loggedInUser;


    public function indexAction()
    {
        $session = new \Symfony\Component\HttpFoundation\Session\Session();
        $session->start();
        $loggedInUser = $session->get("User");
        
        if($loggedInUser){
            $this->loggedInUser = $loggedInUser;
            return $this->userDashboard();
        }else{
             return $this->render('FrontEndBundle:Index:index.html.twig');
        }
    }
    
    
    
    public function userDashboard()
    {
        $em = $this->getDoctrine()->getManager();
        $queryCourse = $em->createQuery("SELECT c.id, c.name, c.description, s.name as subcatname, cat.name as catname 
                                         FROM Queskey\FrontEndBundle\Entity\Course c 
                                         JOIN c.subcat s
                                         JOIN s.cat cat
                                         WHERE c.published = 1");
        $queryCourse->setMaxResults(10);
        $courses = $queryCourse->getResult();
//        var_dump($courses);
//        die;
        
        $querySubCategories = $em->createQuery("SELECT s.id, s.name, c.id as cid, c.name as cname FROM Queskey\FrontEndBundle\Entity\SubCategory s JOIN s.cat c where s.published = 1 and c.published = 1 order by cid desc");
        $subCategories = $querySubCategories->getResult();
//        var_dump($subCategories);
//        die;
        
        $getMyCourse = new \Queskey\FrontEndBundle\Model\CheckSubscription();
        $myCourses = $getMyCourse->myCourses($this->loggedInUser, $em);
        
        return $this->render("FrontEndBundle:Index:dashboard.html.twig",array("courses"=>$courses, "subCategories" => $subCategories, "myCourses"=>$myCourses));
    }
}
