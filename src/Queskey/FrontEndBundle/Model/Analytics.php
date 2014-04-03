<?php

namespace Queskey\FrontEndBundle\Model; 

class Analytics 
{
    public function generateAnalytics($userId, $em, $subjects)
    {
        var_dump($subjects);
            $query = $em->createQuery('SELECT q.subjectid,
                                       COUNT(q.answer) as answered, 
                                       SUM(q.iscorrect) as correct,
                                       SUM(q.timetaken) as totalTime
                                       FROM Queskey\FrontEndBundle\Entity\Quesattempted q
                                       WHERE q.subjectid IN (:subjectId)
                                       GROUP BY q.subjectid')->setParameter('subjectId', $subjects);
            $result = $query->getResult();
        $queryUser = $em->createQuery('SELECT q.subjectid,
                                       COUNT(q.answer) as answered, 
                                       SUM(q.iscorrect) as correct,
                                       SUM(q.timetaken) as totalTime
                                       FROM Queskey\FrontEndBundle\Entity\Quesattempted q
                                       WHERE q.subjectid IN (:subjectId)
                                       AND q.userid = :userId
                                       GROUP BY q.subjectid')->setParameters(array('subjectId'=> $subjects,
                                                                                  'userId'=> $userId->getId()));
            $resultUser = $queryUser->getResult();
            var_dump($result);
            var_dump($resultUser);
            
            
        foreach ($subjects as $subject)
        {
            $analytics = new \Queskey\FrontEndBundle\Entity\Analytics();
            $analytics->setUserid($userId);
            $analytics->setSubjectid($subject);
            foreach($resultUser as $array)
            {
                if($array['subjectid'] === $subject)
                $analytics->setAnswered($array['answered']);
            }
            
        }
            die;
        
    }
}

?>
