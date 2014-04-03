<?php

namespace Queskey\FrontEndBundle\Model; 

class Analytics 
{
    public function generateAnalytics($userid, $em, $subjects)
    {
            $query = $em->createQuery('SELECT q.subjectid,
                                       COUNT(q.answer) as answered, 
                                       SUM(q.iscorrect) as correct,
                                       SUM(q.timetaken) as totalTime
                                       FROM Queskey\FrontEndBundle\Entity\Quesattempted q
                                       WHERE q.subjectid IN (:subjectId)
                                       GROUP BY q.subjectid')->setParameter('subjectId', $subjects);
            $result = $query->getResult();
            $userId = $em->getRepository('FrontEndBundle:User')->find($userid->getId());
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
 
            foreach ($subjects as $subject)
            {
                $entry = $em->getRepository('FrontEndBundle:Analytics')->findOneBy(array('userid' => $userId->getId(), 'subjectid' => $subject));
                if($entry)
                {
                    $this->update($entry, $subject, $result, $resultUser, $em);
                }
                else
                {
                    $this->persist($subject, $userId, $result, $resultUser, $em);
                }
            }
            
            return;
            
            
        }
        
        
        public function persist($subject, $userId, $result, $resultUser, $em)
        {
                $analytics = new \Queskey\FrontEndBundle\Entity\Analytics();
                $analytics->setUserid($userId);
                $analytics->setSubjectid($subject);
                foreach($resultUser as $array)
                {
                    if($array['subjectid'] === $subject)
                    {
                        $analytics->setAnswered($array['answered']);
                        $analytics->setCorrect($array['correct']);
                        $analytics->setTimetaken($array['totalTime']);
                    }
                }
            
                foreach($result as $array)
                {
                    if($array['subjectid'] === $subject)
                    {
                        $analytics->setCommunityanswered($array['answered']);
                        $analytics->setCommunitycorrect($array['correct']);
                        $analytics->setCommunitytimetaken($array['totalTime']);
                    }
                }
               
                $em->persist($analytics);
                $em->flush();
                return;
        }
        
        public function update($entry, $subject, $result, $resultUser, $em)
        {
            foreach($resultUser as $array)
                {
                    if($array['subjectid'] === $subject)
                    {
                        $entry->setAnswered($array['answered']);
                        $entry->setCorrect($array['correct']);
                        $entry->setTimetaken($array['totalTime']);
                    }
                }
            
                foreach($result as $array)
                {
                    if($array['subjectid'] === $subject)
                    {
                        $entry->setCommunityanswered($array['answered']);
                        $entry->setCommunitycorrect($array['correct']);
                        $entry->setCommunitytimetaken($array['totalTime']);
                    }
                }
               
                $em->flush();
                return;
        }

 
}
?>
