<?php

namespace Queskey\FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends Controller
{
    public function newAction()
    {
        $session = new \Symfony\Component\HttpFoundation\Session\Session();
        $session->start();
        $loggedInUser = $session->get("User");

        if($loggedInUser)
        {
            
        $request = $this->get('request');
        
        if($request->isXmlHttpRequest() && $request->getMethod() == 'POST')
        {
        
            $subscription = $request->request->all();
            $newSubscription = new \Queskey\FrontEndBundle\Entity\Subscriptions();
            $newSubscription->setUserid($loggedInUser->getId());
            $newSubscription->setCourseid($subscription['course_id']);
            $newSubscription->setPaymentplanid($subscription['pid']);
           
            $date = new \DateTime(date('Y-m-d H:i:s'));
            
            $newSubscription->setJoiningtime($date);
            
            $days = $subscription['expiryTime'].' days';
            $expiryDays = clone $date;
            
            $newSubscription->setExpirytime($expiryDays->modify($days));
       
            $em = $this->getDoctrine()->getManager();

            $query = $em->createQuery('SELECT s.courseid 
                                       FROM Queskey\FrontEndBundle\Entity\Subscriptions s 
                                       WHERE s.userid = :id')->setParameter('id', $newSubscription->getUserid());
            
            $query_reult = $query->getResult();
            
            $flag = true;
            
            if($query_reult)
            {
                foreach ($query_reult as $key => $value) 
                    {
                        if($value['courseid'] == $subscription['course_id'])
                        {
                            $flag = false;
                            break;
                        }
                    } 
            }
            else
            {
                $em->persist($newSubscription);
                $em->flush();
                $flag = false;
            }
            
            if($flag == true)
            {
                $em->persist($newSubscription);
                $em->flush();
            }
            
            else
            {
                return new Response("error");
            }

            return new Response("success");
            
        }
        else 
        {
            return new Response("error");
        }
        }
        else
        {
            return $this->render('FrontEndBundle:Common:pleaseLogin.html.twig');
        }
    }
}