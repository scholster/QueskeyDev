<?php

namespace Queskey\FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Stopwatch\Stopwatch;

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
//        $connection = $this->getDoctrine()->getManager()->getConnection();
//        $correct = array(1,1,0,0,1,1);
//        $time = array(50,100,150,200,10,20,60,90,120,170);
//        $stopwatch = new Stopwatch();
//        $stopwatch->start('insertionLoop');
//        
//        for($a=98950; $a<100112; $a++)
//        {
//            $para = array('id'=>$a,
//                          'name'=>$a."user",
//                          'email'=>$a."eamil@eamil.com",
//                          'password'=>$a."pwd",
//                          'admin'=>0);
//        
//            $user = "INSERT INTO user(id, name, email, password, admin) VALUES(:id, :name, :email, :password, :admin)";
//            $stm = $connection->prepare($user);
//            $stm->execute($para);
//            
//            for($b=0; $b<5; $b++)
//            {
//                $param = array('qid'=>  mt_rand(1000000, 20000000),
//                               'sid'=>  mt_rand(1, 13),
//                               'userid'=>$a,
//                               'ans'=>mt_rand(1,4),
//                               'is'=>mt_rand(0,1),
//                               'time'=>mt_rand(50,200) );
//                $ques = "INSERT INTO quesattempted(quesId, subjectId, userId, answer, isCorrect, timeTaken)
//                         VALUES(:qid, :sid, :userid, :ans, :is, :time)";
//                $qwe = $connection->prepare($ques);
//                $qwe->execute($param);
//            }
//        }
        
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
        
//        $userId = $em->getRepository('FrontEndBundle:User')->find($this->loggedInUser->getId());
//        $test = array(1,2,3,4,5,6,7,8,9,10,11,12,13); 
//        $q = $em->createQuery("SELECT ");
        $stopwatch = new Stopwatch();
        $stopwatch->start('insertionLoop');
//        for($i = 0; $i < 2000; $i++ )
//        {
//            $skey = array_rand($test, 1);
//            $this->dbPersist($userId, $test, $skey, $em);
//        }
      
        $analytics = new \Queskey\FrontEndBundle\Model\Analytics();
        $analytics->generateAnalytics($this->loggedInUser, $em, $subjects);
        $event = $stopwatch->stop('insertionLoop');
        var_dump($event->getDuration());
        var_dump($event->getMemory());
        die;
//        
//        return $this->render('FrontEndBundle:Quiz:thanks.html.twig', array('id'=>$id));
    }
    
    
    public function dbPersist($userId, $subjects, $skey, $em)
    {
        $ques = new \Queskey\FrontEndBundle\Entity\Quesattempted();
        $answers = array(1,2,3,4);
         
        $iscorrect = array(1,1,0,1);
        
        $time = array(50,100,150,200,10,20,60,90,120,170);
        
        
            $rand = mt_rand(20, 1000000);
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
    
    public function quizAction($id)
    {
        if($this->loggedInUser)
        {
            $em = $this->getDoctrine()->getManager();
            $courseQuery = $em->createQuery('SELECT q.id
                                             FROM Queskey\FrontEndBundle\Entity\Quiz quiz
                                             JOIN quiz.courseid q
                                             WHERE quiz.id = :id')->setParameter('id', $id);
            
            $courseId = $courseQuery->getResult();
            $subscription = $this->checkSubscription($courseId[0]['id'], $em);
            
            if($subscription['flag'] == 1 && $subscription['expired'] == 0)
            {
                $query = $em->createQuery('SELECT quiz.id, q.id as qId, q.question, q.option1, q.option2, q.option3, q.option4, q.option5
                                           FROM Queskey\FrontEndBundle\Entity\Questions q
                                           JOIN q.quizid quiz
                                           WHERE quiz.id = :id')->setParameter('id', $id);
                $result = $query->getResult();
                if($result)
                {
                    return $this->render('FrontEndBundle:Quiz:quiz.html.twig', array('questions'=>$result));
                }
                else
                {
                    return $this->render('FrontEndBundle:Common:notFound.html.twig');
                }
              
            }
            else
            {
                return $this->render('FrontEndBundle:Common:notSubscribed.html.twig');
            }
        }
        
        else
        {
            return $this->render('FrontEndBundle:Common:pleaseLogin.html.twig');
        }
    }
    
    
    

    public function subjectQuizAction($id)
    {
        if($this->loggedInUser)
        {
        
            $em = $this->getDoctrine()->getManager();
            $subscription = $this->checkSubjectSubscription($id, $em);
            if($subscription['flag'] == 1 && $subscription['expired'] == 0)
            {
                $query = $em->createQuery('SELECT quiz.id
                                           FROM Queskey\FrontEndBundle\Entity\Quiz quiz
                                           WHERE quiz.subjectid = :id')->setParameter('id', $id);
                $result = $query->getResult();
                if($result)
                {
                    return $this->render('FrontEndBundle:Quiz:list.html.twig', array('list'=>$result[0]));
                }
                else
                {
                    return $this->render('FrontEndBundle:Common:notFound.html.twig');
                }
                return 0;
            }
            
            else
            {
                return $this->render('FrontEndBundle:Common:notSubscribed.html.twig');
            }
        
        }
        else
        {
            return $this->render('FrontEndBundle:Common:pleaseLogin.html.twig');
        }
    }
    
    
    
    public function topicQuizAction($id)
    {
        if($this->loggedInUser)
        {
        
            $em = $this->getDoctrine()->getManager();
            $subscription = $this->checkTopicSubscription($id, $em);
            if($subscription['flag'] == 1 && $subscription['expired'] == 0)
            {
                $query = $em->createQuery('SELECT quiz.id
                                           FROM Queskey\FrontEndBundle\Entity\Quiz quiz
                                           WHERE quiz.topicid = :id')->setParameter('id', $id);
                $result = $query->getResult();
                if($result)
                {
                    return $this->render('FrontEndBundle:Quiz:list.html.twig', array('list'=>$result[0]));
                }
                else
                {
                    return $this->render('FrontEndBundle:Common:notFound.html.twig');
                }
                return 0;
            }
            
            else
            {
                return $this->render('FrontEndBundle:Common:notSubscribed.html.twig');
            }
        
        }
        else
        {
            return $this->render('FrontEndBundle:Common:pleaseLogin.html.twig');
        }
    }
    
    
    
    
    public function lessonQuizAction($id)
    {
        if($this->loggedInUser)
        {
        
            $em = $this->getDoctrine()->getManager();
            $subscription = $this->checkLessonSubscription($id, $em);
            if($subscription['flag'] == 1 && $subscription['expired'] == 0)
            {
                $query = $em->createQuery('SELECT quiz.id
                                           FROM Queskey\FrontEndBundle\Entity\Quiz quiz
                                           WHERE quiz.lessonid = :id')->setParameter('id', $id);
                $result = $query->getResult();
                if($result)
                {
                    return $this->render('FrontEndBundle:Quiz:list.html.twig', array('list'=>$result[0]));
                }
                else
                {
                    return $this->render('FrontEndBundle:Common:notFound.html.twig');
                }
                return 0;
            }
            
            else
            {
                return $this->render('FrontEndBundle:Common:notSubscribed.html.twig');
            }
        
        }
        else
        {
            return $this->render('FrontEndBundle:Common:pleaseLogin.html.twig');
        }
    }
    
    
    
    
    public function checkSubjectSubscription($id, $em)
    {
        $query = $em->createQuery('SELECT c.id
                                   FROM Queskey\FrontEndBundle\Entity\Coursesubjects s
                                   JOIN s.courseid c
                                   WHERE s.id = :id')->setParameter('id', $id);
        $result = $query->getResult();
        return $this->checkSubscription($result[0]['id'], $em);
    }
    
    
    
    public function checkTopicSubscription($id, $em)
    {
        $query = $em->createQuery('SELECT c.id
                                   FROM Queskey\FrontEndBundle\Entity\Coursetopics t
                                   JOIN t.subjectid s
                                   JOIN s.courseid c
                                   WHERE t.id = :id')->setParameter('id', $id);
        $result = $query->getResult();
        return $this->checkSubscription($result[0]['id'], $em);
    }
    
    
    
    public function checkLessonSubscription($id, $em)
    {
        $query = $em->createQuery('SELECT c.id
                                   FROM Queskey\FrontEndBundle\Entity\Courselessons l
                                   JOIN l.topicid t
                                   JOIN t.subjectid s
                                   JOIN s.courseid c
                                   WHERE l.id = :id')->setParameter('id', $id);
        $result = $query->getResult();
        return $this->checkSubscription($result[0]['id'], $em);
    }
    
    
    public function checkSubscription($courseId, $em)
    {
        if($this->loggedInUser)
        {
            $userId = $this->loggedInUser->getId();
            $course = new \Queskey\FrontEndBundle\Model\CheckSubscription();
            $course_info = $course->checkIfSubscribed($userId, $courseId, $em);
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
    

}

?>
