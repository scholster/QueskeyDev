<?php

namespace Queskey\FrontEndBundle\Model;

class CheckSubscription
{
    public function myCourses($userid, $em)
    {

        $query = $em->createQuery('SELECT c.id, c.name, c.description, subcat.name as subcatname, cat.name as catname 
                                   FROM Queskey\FrontEndBundle\Entity\Subscriptions s
                                   JOIN s.courseid c
                                   JOIN s.userid u
                                   JOIN c.subcat subcat
                                   JOIN subcat.cat cat
                                   WHERE u.id = :userId ')->setParameter('userId', $userid->getId());
        $result = $query->getResult();

        return $result;
    }
    
    public function checkIfSubscribed($userid, $courseid, $em)
    {
        return $em->getRepository('FrontEndBundle:Subscriptions')->findOneBy(array('userid'=>$userid, 'courseid'=>$courseid));
    }
}


?>