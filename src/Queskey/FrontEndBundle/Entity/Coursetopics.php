<?php

namespace Queskey\FrontEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Coursetopics
 */
class Coursetopics
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $topicname;

    /**
     * @var string
     */
    private $topicdescription;

    /**
     * @var boolean
     */
    private $topictype;

    /**
     * @var \Queskey\FrontEndBundle\Entity\Coursesubjects
     */
    private $subjectid;


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
     * Set topicname
     *
     * @param string $topicname
     * @return Coursetopics
     */
    public function setTopicname($topicname)
    {
        $this->topicname = $topicname;

        return $this;
    }

    /**
     * Get topicname
     *
     * @return string 
     */
    public function getTopicname()
    {
        return $this->topicname;
    }

    /**
     * Set topicdescription
     *
     * @param string $topicdescription
     * @return Coursetopics
     */
    public function setTopicdescription($topicdescription)
    {
        $this->topicdescription = $topicdescription;

        return $this;
    }

    /**
     * Get topicdescription
     *
     * @return string 
     */
    public function getTopicdescription()
    {
        return $this->topicdescription;
    }

    /**
     * Set topictype
     *
     * @param boolean $topictype
     * @return Coursetopics
     */
    public function setTopictype($topictype)
    {
        $this->topictype = $topictype;

        return $this;
    }

    /**
     * Get topictype
     *
     * @return boolean 
     */
    public function getTopictype()
    {
        return $this->topictype;
    }

    /**
     * Set subjectid
     *
     * @param \Queskey\FrontEndBundle\Entity\Coursesubjects $subjectid
     * @return Coursetopics
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
}
