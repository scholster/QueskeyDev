<?php

namespace Queskey\FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class LoginController extends Controller
{
    public function loginAction()
    {   
        $request=$this->get('request');
        if($request->isXmlHttpRequest() && $request->getMethod()=="POST")
        {
            $data = $request->request->all();
            $em = $this->getDoctrine()->getManager();
            
            $userRepository = $em->getRepository('FrontEndBundle:User');
            $user = $userRepository->findOneBy(array("email"=>$data['email']));
            if($user && $user->getPassword()== sha1($data['pwd']))
            {
                $userLogin = new \Queskey\FrontEndBundle\Model\UserLogin($user->getId(), $user->getName(), $user->getEmail(), $user->getAdmin());
                $session = new \Symfony\Component\HttpFoundation\Session\Session();
                $session->start();
                $session->set("User", $userLogin);
                $response = new Response(json_encode(array("status"=>"success")));
                return $response;
            }else{
                $response = new Response(json_encode(array("status"=>"fail","message"=>"Invalid Email Id, Password combination")));
                return $response;
            }
            
            
        }
        
         return $this->render('FrontEndBundle:Common:notFound.html.twig');
    }
    
    public function logoutAction()
    {
        $session = new \Symfony\Component\HttpFoundation\Session\Session();
        $session->start();
        $session->invalidate();
        
        return $this->redirect($this->generateUrl('index_frontEnd'));
    }
}
