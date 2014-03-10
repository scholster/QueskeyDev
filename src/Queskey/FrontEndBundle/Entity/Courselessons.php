<?php

namespace Queskey\FrontEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Courselessons
 */
class Courselessons
{
    /**
     * @var string
     */
    private $lessonname;

    /**
     * @var boolean
     */
    private $lessontype;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Queskey\FrontEndBundle\Entity\Coursetopics
     */
    private $topicid;


    /**
     * Set lessonname
     *
     * @param string $lessonname
     * @return Courselessons
     */
    public function setLessonname($lessonname)
    {
        $this->lessonname = $lessonname;

        return $this;
    }

    /**
     * Get lessonname
     *
     * @return string 
     */
    public function getLessonname()
    {
        return $this->lessonname;
    }

    /**
     * Set lessontype
     *
     * @param boolean $lessontype
     * @return Courselessons
     */
    public function setLessontype($lessontype)
    {
        $this->lessontype = $lessontype;

        return $this;
    }

    /**
     * Get lessontype
     *
     * @return boolean 
     */
    public function getLessontype()
    {
        return $this->lessontype;
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
     * @return Courselessons
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
}
