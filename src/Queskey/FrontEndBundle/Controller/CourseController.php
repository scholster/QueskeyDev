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
            
            $insId = $this->checkIfAdmin($id, $this->getEm());
            
            if($insId)
            {
        
//            if($this->loggedInUser->getAdmin() && $this->loggedInUser->getId() == $insId[0]['id'])
            if($this->loggedInUser->getId() == $insId[0]['id'])            
            {
                return $this->redirect($this->generateUrl('courseAdmin', array('id'=>$id)));
            }
            else
            {
                $array['courseInfo'] = $this->fetchCourseDetails($id, $this->getEm());
                $array['courseSubjects'] = $this->fetchCourseSubjects($id, $this->getEm());
                $array['subscriptionFlag'] = $this->checkSubscription($id, $this->getEm());
                $array['myCourses'] = $this->myCourses();
                
                if($array['courseInfo'])
                {
                                       
                    if($array['subscriptionFlag']['flag'] && !$array['subscriptionFlag']['expired'])
                    {
                        return $this->render('FrontEndBundle:Course:courseHome.html.twig',array('course'=>$array['courseInfo'], 'subjects'=>$array['courseSubjects'],'subscriptionFlag'=>$array['subscriptionFlag'], 'myCourses'=>$array['myCourses']));
                    }
                    else
                    {
                        return $this->render('FrontEndBundle:Course:courseDetails.html.twig', array('course'=>$array['courseInfo'], 'subscriptionFlag'=>$array['subscriptionFlag'], 'subjects'=>$array['courseSubjects'], 'myCourses'=>$array['myCourses']));
                    }
                }
                else
                {
                    return $this->render('FrontEndBundle:Common:notFound.html.twig');
                }
            }
        }
        
        else
        {
            return $this->render('FrontEndBundle:Common:notFound.html.twig');
        }
        }
        
        else
        {
//            return $this->render('FrontEndBundle:Common:pleaseLogin.html.twig');
              $array['courseInfo'] = $this->fetchCourseDetails($id, $this->getEm());
              $array['courseSubjects'] = $this->fetchCourseSubjects($id, $this->getEm());
              $array['subscriptionFlag'] = $this->checkSubscription($id, $this->getEm());
              $array['myCourses'] = $this->myCourses();
              
              if($array['courseInfo'])
              {
                return $this->render('FrontEndBundle:Course:courseDetails.html.twig',array('course'=>$array['courseInfo'],'subjects'=>$array['courseSubjects'], 'subscriptionFlag'=>$array['subscriptionFlag'], 'myCourses'=>$array['myCourses']));
              }
              else
              {
              return $this->render('FrontEndBundle:Common:notFound.html.twig');
              }
        }
}
        
    
    
    
    
    public function adminAction($id)
    {
        $insId = $this->checkIfAdmin($id, $this->getEm());
//        if($this->loggedInUser->getAdmin() && $this->loggedInUser->getId() == $insId[0]['id'])
        if($this->loggedInUser)
        {
            if($this->loggedInUser->getId() == $insId[0]['id'])
            {
            
            $array['courseInfo'] = $this->fetchCourseDetails($id, $this->getEm());
            $array['courseSubjects'] = $this->fetchCourseSubjects($id, $this->getEm());
            $array['myCourses'] = $this->myCourses();
            if($array['courseInfo'])
            {
//                $url = $this->generateUrl('course', array('id'=>$id));
//                $array['courseInfo']['url'] = $url;
                return $this->render('FrontEndBundle:Course:courseAdmin.html.twig',array('course'=>$array['courseInfo'], 'subjects'=>$array['courseSubjects'], 'subscriptionFlag'=>array('expired'=>0, 'flag'=>1), 'myCourses'=>$array['myCourses']));
            }
            else
            {
                return $this->render('FrontEndBundle:Common:notFound.html.twig');
            }
            
            }
        
            else
            {
                return $this->render('FrontEndBundle:Common:notFound.html.twig');
            }
        }
        else
        {
            return $this->render('FrontEndBundle:Common:pleaseLogin.html.twig');
        }
   }
    
   
   
   public function contentAction($id, $sId)
    {
       $subscriptionFlag = $this->checkSubscription($id, $this->getEm());
       $courseInfo = $this->fetchCourseDetails($id, $this->getEm());
       $topics = $this->fetchCourseTopics($sId, $this->getEm());
       $myCourses = $this->myCourses();
//        if($this->loggedInUser)
//        {
            if($topics)
            {
                return $this->render('FrontEndBundle:Course:courseContent.html.twig', array('course'=> $courseInfo,'topics'=>$topics, 'subscriptionFlag'=>$subscriptionFlag, 'myCourses' =>$myCourses));
            }
            else
            {
                return $this->render('FrontEndBundle:Common:notFound.html.twig');
            }  
//        }
//        else
//        {
//            return $this->render('FrontEndBundle:Default:notFound.html.twig');
//        }
    }
    
    
    
    public function contentAllAction($id, $tId)
    {
        if($this->loggedInUser)
        {
            $subscriptionFlag = $this->checkSubscription($id, $this->getEm());
            if(!$subscriptionFlag['expired'] && $subscriptionFlag['flag'])
            {
                $courseInfo = $this->fetchCourseDetails($id, $this->getEm());
                $lessons = $this->fetchCourseLessons($tId, $this->getEm());
                $myCourses = $this->myCourses();
                if($lessons)
                {
                    return $this->render('FrontEndBundle:Course:courseAllContent.html.twig',array('lessons'=>$lessons, 'course'=>$courseInfo ,'subscriptionFlag'=>$subscriptionFlag, 'myCourses'=>$myCourses));
                }
                else
                {
                    return $this->render('FrontEndBundle:Common:notFound.html.twig');
                }
                
            }
            else
            {
                return $this->render('FrontEndBundle:Common:notFound.html.twig');
            }
        }
        else
        {
            return $this->render('FrontEndBundle:Common:pleaseLogin.html.twig');
        }
    }

    



    public function fetchCourseDetails($courseId, $em)
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
                                          WHERE c.id = :id')->setParameter('id', $courseId);
        
        $result = $course_query->getResult();
        return $result[0];  
    }
    
    
    
     public function fetchCourseSubjects($courseId, $em)
    {
         $query = $em->createQuery('SELECT c.id, s.id as sId, s.subjectname as name, s.subjectdescription as description
                                    FROM Queskey\FrontEndBundle\Entity\Coursesubjects s
                                    JOIN s.courseid c
                                    WHERE c.id = :id' )->setParameter('id', $courseId);
        
         $array = $query->getResult();
         foreach($array as $key=>$value) 
         {
             $array[$key]['url'] = $this->generateUrl('courseSubject', array('id'=>$value['id'], 'sId'=>$value['sId']));
         }
         
        return $array;  
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
    
    
    public function fetchCourseTopics($sId, $em)
    {
        $query = $em->createQuery('SELECT c.id, c.name as cname, s.id as sId, s.subjectname,
                                          t.id as tId, t.topicname as name, t.topicdescription as description
                                    FROM Queskey\FrontEndBundle\Entity\Coursetopics t
                                    JOIN t.subjectid s
                                    JOIN s.courseid c
                                    WHERE s.id = :id' )->setParameter('id', $sId);
        
         $array = $query->getResult();
         
         foreach($array as $key=>$value) 
         {
             $array[$key]['url'] = $this->generateUrl('courseTopic', array('id'=>$value['id'], 'sId'=>$value['sId'], 'tId'=>$value['tId']));
         }

        return $array;
    }
    
    
    public function getEm()
    {
        return $this->getDoctrine()->getManager();
    }
    
    
    public function fetchCourseLessons($tId, $em)
    {
        $query = $em->createQuery('SELECT c.id, c.name as cname, s.id as sId, s.subjectname,
                                          t.id as tId, t.topicname, lid.id as leid, lid.lessonname, l.id as lcid, l.contentname, l.content
                                    FROM Queskey\FrontEndBundle\Entity\Lessoncontents l
                                    JOIN l.lessonid lid
                                    JOIN lid.topicid t
                                    JOIN t.subjectid s
                                    JOIN s.courseid c
                                    WHERE t.id = :id' )->setParameter('id', $tId);
        
         $array = $query->getResult();
//         var_dump($array);
//         die;
//         foreach($array as $key=>$value) 
//         {
//             $array[$key]['url'] = $this->generateUrl('courseTopic', array('id'=>$value['id'], 'sId'=>$value['sId'], 'tId'=>$value['tId']));
//         }
         
        return $array;
    }
    
    public function myCourses()
    {
        $getMyCourse = new \Queskey\FrontEndBundle\Model\CheckSubscription();
        $myCourses = $getMyCourse->myCourses($this->loggedInUser, $this->getEm());
        return $myCourses;
    }
}

?>
