<?php

namespace Queskey\FrontEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Courseinnerlessons
 */
class Courseinnerlessons
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $innerlessonname;

    /**
     * @var \Queskey\FrontEndBundle\Entity\Courselessons
     */
    private $lessonid;


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
     * Set innerlessonname
     *
     * @param string $innerlessonname
     * @return Courseinnerlessons
     */
    public function setInnerlessonname($innerlessonname)
    {
        $this->innerlessonname = $innerlessonname;

        return $this;
    }

    /**
     * Get innerlessonname
     *
     * @return string 
     */
    public function getInnerlessonname()
    {
        return $this->innerlessonname;
    }

    /**
     * Set lessonid
     *
     * @param \Queskey\FrontEndBundle\Entity\Courselessons $lessonid
     * @return Courseinnerlessons
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
}
