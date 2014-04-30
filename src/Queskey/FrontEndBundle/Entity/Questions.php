<?php

namespace Queskey\FrontEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Questions
 */
class Questions
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $question;

    /**
     * @var string
     */
    private $option1;

    /**
     * @var string
     */
    private $option2;

    /**
     * @var string
     */
    private $option3;

    /**
     * @var string
     */
    private $option4;

    /**
     * @var string
     */
    private $option5;

    /**
     * @var integer
     */
    private $correctoption;

    /**
     * @var string
     */
    private $solution;

    /**
     * @var integer
     */
    private $level;

    /**
     * @var \Queskey\FrontEndBundle\Entity\Coursetopics
     */
    private $topicid;

    /**
     * @var \Queskey\FrontEndBundle\Entity\Courselessons
     */
    private $lessonid;

    /**
     * @var \Queskey\FrontEndBundle\Entity\QuestionData
     */
    private $dataid;

    /**
     * @var \Queskey\FrontEndBundle\Entity\Coursesubjects
     */
    private $subjectid;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $quizid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->quizid = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set question
     *
     * @param string $question
     * @return Questions
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set option1
     *
     * @param string $option1
     * @return Questions
     */
    public function setOption1($option1)
    {
        $this->option1 = $option1;

        return $this;
    }

    /**
     * Get option1
     *
     * @return string 
     */
    public function getOption1()
    {
        return $this->option1;
    }

    /**
     * Set option2
     *
     * @param string $option2
     * @return Questions
     */
    public function setOption2($option2)
    {
        $this->option2 = $option2;

        return $this;
    }

    /**
     * Get option2
     *
     * @return string 
     */
    public function getOption2()
    {
        return $this->option2;
    }

    /**
     * Set option3
     *
     * @param string $option3
     * @return Questions
     */
    public function setOption3($option3)
    {
        $this->option3 = $option3;

        return $this;
    }

    /**
     * Get option3
     *
     * @return string 
     */
    public function getOption3()
    {
        return $this->option3;
    }

    /**
     * Set option4
     *
     * @param string $option4
     * @return Questions
     */
    public function setOption4($option4)
    {
        $this->option4 = $option4;

        return $this;
    }

    /**
     * Get option4
     *
     * @return string 
     */
    public function getOption4()
    {
        return $this->option4;
    }

    /**
     * Set option5
     *
     * @param string $option5
     * @return Questions
     */
    public function setOption5($option5)
    {
        $this->option5 = $option5;

        return $this;
    }

    /**
     * Get option5
     *
     * @return string 
     */
    public function getOption5()
    {
        return $this->option5;
    }

    /**
     * Set correctoption
     *
     * @param integer $correctoption
     * @return Questions
     */
    public function setCorrectoption($correctoption)
    {
        $this->correctoption = $correctoption;

        return $this;
    }

    /**
     * Get correctoption
     *
     * @return integer 
     */
    public function getCorrectoption()
    {
        return $this->correctoption;
    }

    /**
     * Set solution
     *
     * @param string $solution
     * @return Questions
     */
    public function setSolution($solution)
    {
        $this->solution = $solution;

        return $this;
    }

    /**
     * Get solution
     *
     * @return string 
     */
    public function getSolution()
    {
        return $this->solution;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return Questions
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set topicid
     *
     * @param \Queskey\FrontEndBundle\Entity\Coursetopics $topicid
     * @return Questions
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
     * @return Questions
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
     * Set dataid
     *
     * @param \Queskey\FrontEndBundle\Entity\QuestionData $dataid
     * @return Questions
     */
    public function setDataid(\Queskey\FrontEndBundle\Entity\QuestionData $dataid = null)
    {
        $this->dataid = $dataid;

        return $this;
    }

    /**
     * Get dataid
     *
     * @return \Queskey\FrontEndBundle\Entity\QuestionData 
     */
    public function getDataid()
    {
        return $this->dataid;
    }

    /**
     * Set subjectid
     *
     * @param \Queskey\FrontEndBundle\Entity\Coursesubjects $subjectid
     * @return Questions
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
     * Add quizid
     *
     * @param \Queskey\FrontEndBundle\Entity\Quiz $quizid
     * @return Questions
     */
    public function addQuizid(\Queskey\FrontEndBundle\Entity\Quiz $quizid)
    {
        $this->quizid[] = $quizid;

        return $this;
    }

    /**
     * Remove quizid
     *
     * @param \Queskey\FrontEndBundle\Entity\Quiz $quizid
     */
    public function removeQuizid(\Queskey\FrontEndBundle\Entity\Quiz $quizid)
    {
        $this->quizid->removeElement($quizid);
    }

    /**
     * Get quizid
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuizid()
    {
        return $this->quizid;
    }
}
