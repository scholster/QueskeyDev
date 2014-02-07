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
    
    
    
    public function userDashboard(){
        
        
        return $this->render("FrontEndBundle:Index:dashboard.html.twig");
    }
}
