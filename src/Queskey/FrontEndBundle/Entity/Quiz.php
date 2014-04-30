<?php

namespace Queskey\FrontEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Quiz
 */
class Quiz
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Queskey\FrontEndBundle\Entity\Coursetopics
     */
    private $topicid;

    /**
     * @var \Queskey\FrontEndBundle\Entity\Courselessons
     */
    private $lessonid;

    /**
     * @var \Queskey\FrontEndBundle\Entity\Coursesubjects
     */
    private $subjectid;

    /**
     * @var \Queskey\FrontEndBundle\Entity\Quiztypes
     */
    private $quiztype;

    /**
     * @var \Queskey\FrontEndBundle\Entity\Course
     */
    private $courseid;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $questionid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questionid = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set topicid
     *
     * @param \Queskey\FrontEndBundle\Entity\Coursetopics $topicid
     * @return Quiz
     */
    public function setTopicid(\Queskey\FrontEndBundle\Entity\Coursetopics $topicid = null)
    {
        $this->topicid = $topicid;

        return $this;
    }

    /**
     * Get topicid
     *
     * @return \Queskey\FrontEndBundle\Entity\Coursetopics 
     */
    public function getTopicid()
    {
        return $this->topicid;
    }

    /**
     * Set lessonid
     *
     * @param \Queskey\FrontEndBundle\Entity\Courselessons $lessonid
     * @return Quiz
     */
    public function setLessonid(\Queskey\FrontEndBundle\Entity\Courselessons $lessonid = null)
    {
        $this->lessonid = $lessonid;

        return $this;
    }

    /**
     * Get lessonid
     *
     * @return \Queskey\FrontEndBundle\Entity\Courselessons 
     */
    public function getLessonid()
    {
        return $this->lessonid;
    }

    /**
     * Set subjectid
     *
     * @param \Queskey\FrontEndBundle\Entity\Coursesubjects $subjectid
     * @return Quiz
     */
    public function setSubjectid(\Queskey\FrontEndBundle\Entity\Coursesubjects $subjectid = null)
    {
        $this->subjectid = $subjectid;

        return $this;
    }

    /**
     * Get subjectid
     *
     * @return \Queskey\FrontEndBundle\Entity\Coursesubjects 
     */
    public function getSubjectid()
    {
        return $this->subjectid;
    }

    /**
     * Set quiztype
     *
     * @param \Queskey\FrontEndBundle\Entity\Quiztypes $quiztype
     * @return Quiz
     */
    public function setQuiztype(\Queskey\FrontEndBundle\Entity\Quiztypes $quiztype = null)
    {
        $this->quiztype = $quiztype;

        return $this;
    }

    /**
     * Get quiztype
     *
     * @return \Queskey\FrontEndBundle\Entity\Quiztypes 
     */
    public function getQuiztype()
    {
        return $this->quiztype;
    }

    /**
     * Set courseid
     *
     * @param \Queskey\FrontEndBundle\Entity\Course $courseid
     * @return Quiz
     */
    public function setCourseid(\Queskey\FrontEndBundle\Entity\Course $courseid = null)
    {
        $this->courseid = $courseid;

        return $this;
    }

    /**
     * Get courseid
     *
     * @return \Queskey\FrontEndBundle\Entity\Course 
     */
    public function getCourseid()
    {
        return $this->courseid;
    }

    /**
     * Add questionid
     *
     * @param \Queskey\FrontEndBundle\Entity\Questions $questionid
     * @return Quiz
     */
    public function addQuestionid(\Queskey\FrontEndBundle\Entity\Questions $questionid)
    {
        $this->questionid[] = $questionid;

        return $this;
    }

    /**
     * Remove questionid
     *
     * @param \Queskey\FrontEndBundle\Entity\Questions $questionid
     */
    public function removeQuestionid(\Queskey\FrontEndBundle\Entity\Questions $questionid)
    {
        $this->questionid->removeElement($questionid);
    }

    /**
     * Get questionid
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuestionid()
    {
        return $this->questionid;
    }
}
