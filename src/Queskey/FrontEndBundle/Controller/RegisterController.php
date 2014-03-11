<?php

namespace Queskey\FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    public function registerAction()
    {
        $request=$this->get("request");
        if($request->isXmlHttpRequest() && $request->getMethod()=="POST")
        {
            $data=$request->request->all();
            $em=$this->getDoctrine()->getManager();
            
            $registerRepository=$em->getRepository('FrontEndBundle:User');
            $user=$registerRepository->findOneBy(array("email"=>$data['email']));
            if($user)
            {
                $response=new Response(json_encode(array("status"=>'fail',"message"=>'Email already in use')));
                return $response;
            }
            else
            {
                $newUser= new \Queskey\FrontEndBundle\Entity\User;
                $newUser->setName($data['username']);
                $newUser->setEmail($data['email']);
                $newUser->setPassword($data['pwd']);
                $newUser->setAdmin(0);
                
                $em->persist($newUser);
                $em->flush();
                
                return $this->userLogin($em, $newUser);
                
            }
        }
    }
    
    public function userLogin($em, $newUser)
    {
        $registerRepository=$em->getRepository('FrontEndBundle:User');
        $user=$registerRepository->findOneBy(array("email"=>$newUser->getEmail()));
        $userLogin = new \Queskey\FrontEndBundle\Model\UserLogin($user->getId(), $user->getName(), $user->getEmail(), $user->getAdmin());
        $session = new \Symfony\Component\HttpFoundation\Session\Session();
        $session->start();
        $session->set("User", $userLogin);
        $response = new Response(json_encode(array("status" => "success", "message"=>'registeration successful. redirecting to dashboard')));
        return $response;
    }
}
