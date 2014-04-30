<?php

namespace Queskey\FrontEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Coursesubjects
 */
class Coursesubjects
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $subjectname;

    /**
     * @var string
     */
    private $subjectdescription;

    /**
     * @var boolean
     */
    private $type;

    /**
     * @var \Queskey\FrontEndBundle\Entity\Course
     */
    private $courseid;


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
     * Set subjectname
     *
     * @param string $subjectname
     * @return Coursesubjects
     */
    public function setSubjectname($subjectname)
    {
        $this->subjectname = $subjectname;

        return $this;
    }

    /**
     * Get subjectname
     *
     * @return string 
     */
    public function getSubjectname()
    {
        return $this->subjectname;
    }

    /**
     * Set subjectdescription
     *
     * @param string $subjectdescription
     * @return Coursesubjects
     */
    public function setSubjectdescription($subjectdescription)
    {
        $this->subjectdescription = $subjectdescription;

        return $this;
    }

    /**
     * Get subjectdescription
     *
     * @return string 
     */
    public function getSubjectdescription()
    {
        return $this->subjectdescription;
    }

    /**
     * Set type
     *
     * @param boolean $type
     * @return Coursesubjects
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return boolean 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set courseid
     *
     * @param \Queskey\FrontEndBundle\Entity\Course $courseid
     * @return Coursesubjects
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
}
