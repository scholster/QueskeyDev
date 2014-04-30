<?php

namespace Queskey\FrontEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lessoncontents
 */
class Lessoncontents
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $content;

    /**
     * @var \Queskey\FrontEndBundle\Entity\Courselessons
     */
    private $lessonid;

    /**
     * @var \Queskey\FrontEndBundle\Entity\Courseinnerlessons
     */
    private $innerlessonid;


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
     * Set content
     *
     * @param string $content
     * @return Lessoncontents
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set lessonid
     *
     * @param \Queskey\FrontEndBundle\Entity\Courselessons $lessonid
     * @return Lessoncontents
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
     * Set innerlessonid
     *
     * @param \Queskey\FrontEndBundle\Entity\Courseinnerlessons $innerlessonid
     * @return Lessoncontents
     */
    public function setInnerlessonid(\Queskey\FrontEndBundle\Entity\Courseinnerlessons $innerlessonid = null)
    {
        $this->innerlessonid = $innerlessonid;

        return $this;
    }

    /**
     * Get innerlessonid
     *
     * @return \Queskey\FrontEndBundle\Entity\Courseinnerlessons 
     */
    public function getInnerlessonid()
    {
        return $this->innerlessonid;
    }
}
