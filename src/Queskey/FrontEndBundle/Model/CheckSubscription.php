<?php

namespace Queskey\FrontEndBundle\Model;

class CheckSubscription
{
    public function myCourses($userid, $em)
    {
        $date = new \DateTime(date('Y-m-d H:i:s'));
        $query = $em->createQuery('SELECT c.id, c.name, c.description, subcat.name as subcatname, cat.name as catname 
                                   FROM Queskey\FrontEndBundle\Entity\Subscriptions s
                                   JOIN s.courseid c
                                   JOIN s.userid u
                                   JOIN c.subcat subcat
                                   JOIN subcat.cat cat
                                   WHERE u.id = :userId 
                                   AND s.expirytime >= :date')->setParameters(array('userId'=>$userid->getId(),
                                                                                'date'=>$date));
        $result = $query->getResult();

        return $result;
    }
    
    public function checkIfSubscribed($userid, $courseid, $em)
    {
        return $em->getRepository('FrontEndBundle:Subscriptions')->findOneBy(array('userid'=>$userid, 'courseid'=>$courseid));
    }
    
//    public function expiry($course_info, $em)
      public function expiry($course_info)
    {
        if($course_info->getExpirytime() <= new \DateTime(date('Y-m-d H:i:s')))
        {
//            $delete = $em->getRepository('FrontEndBundle:Subscriptions')->findOneBy(array('userid'=>$course_info->getUserid(), 'courseid'=>$course_info->getCourseid()));
//            $em->remove($delete);
//            $em->flush();
            return 1;
        }
        return 0;
    }
}


?>