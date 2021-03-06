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
            
            $em = $this->getDoctrine()->getManager();
            $userId = $em->getRepository('FrontEndBundle:User')->find($loggedInUser->getId());
            $course_id = $em->getRepository('FrontEndBundle:Course')->find($subscription['course_id']);
            $pid = $em->getRepository('FrontEndBundle:PaymentPlans')->find($subscription['pid']);
            
            $newSubscription->setUserid($userId);
            $newSubscription->setCourseid($course_id);
            $newSubscription->setPaymentplanid($pid);
           
            $date = new \DateTime(date('Y-m-d H:i:s'));         
            $newSubscription->setJoiningtime($date);            
            $days = $subscription['expiryTime'].' days';
            $expiryDays = clone $date;
            $expiryDays->modify($days);
            $newSubscription->setExpirytime($expiryDays);
            
            try
            {
            $em->persist($newSubscription);
            $em->flush();
            }
            catch(\Exception $e)
            {
                $query = $em->createQuery('UPDATE Queskey\FrontEndBundle\Entity\Subscriptions s
                                           SET s.joiningtime = :date,
                                               s.expirytime = :expiry
                                           WHERE s.userid = :userId
                                           AND s.courseid = :courseId')->setParameters(array('date'=>$date,
                                                                                            'expiry'=>$expiryDays,
                                                                                            'userId'=>$loggedInUser->getId(),
                                                                                            'courseId'=>$subscription['course_id']));
                $query->getResult();
                $msg = $e->getMessage();
                $response = new Response(json_encode(array('success'=>$msg)));
                return $response;
            }
            $response = new Response(json_encode(array('success'=>1)));
            return $response;
    }
      
    else
    {
        $response = new Response(json_encode(array('success'=>0)));
        return $response;
    }
    
    }
    else
    {

            $response = new Response(json_encode(array('success'=>0)));
            return $response;
    }

}
}