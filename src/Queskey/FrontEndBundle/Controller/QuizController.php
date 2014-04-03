<?php

namespace Queskey\FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class QuizController extends Controller 
{
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
        $query = $em->createQuery('SELECT s.id
                                   FROM Queskey\FrontEndBundle\Entity\Coursesubjects s
                                   JOIN s.courseid c
                                   WHERE c.id = :id')->setParameter('id', $id);
        $array = $query->getResult();
        foreach ($array as $value)
        {
            $subjects[] = $value['id'];
        }
        
        $userId = $em->getRepository('FrontEndBundle:User')->find($this->loggedInUser->getId());
       
        for($i = 0; $i < 5; $i++ )
        {
            $skey = array_rand($subjects, 1);
            $this->dbPersist($userId, $subjects, $skey, $em);
        }
      
        $analytics = new \Queskey\FrontEndBundle\Model\Analytics();
        $analytics->generateAnalytics($this->loggedInUser, $em, $subjects);

        
        return $this->render('FrontEndBundle:Quiz:thanks.html.twig', array('id'=>$id));
    }
    
    
    public function dbPersist($userId, $subjects, $skey, $em)
    {
        $ques = new \Queskey\FrontEndBundle\Entity\Quesattempted();
        $answers = array(1,2,3,4);
         
        $iscorrect = array(1,1,0,1);
        
        $time = array(50,100,150,200,10,20,60,90,120,170);
        
        
            $rand = mt_rand(20, 10000);
            $ques->setUserid($userId);
            
            $akey = array_rand($answers, 1);
            $ques->setSubjectid($subjects[$skey]);
            
            $ikey = array_rand($iscorrect, 1);
            $ques->setAnswer($answers[$akey]);
            
            $ques->setIscorrect($iscorrect[$ikey]);
            $tkey = array_rand($time, 1);
            
            $ques->setTimetaken($time[$tkey]);
            $ques->setQuesid($subjects[$skey] - $answers[$akey] - $iscorrect[$ikey] + $time[$tkey] + $rand);
            
            $em->persist($ques);
            $em->flush();
            
            return;
            
            
    }
}

?>
