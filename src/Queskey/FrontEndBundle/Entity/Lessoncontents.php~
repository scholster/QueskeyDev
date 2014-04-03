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
    private $contentname;

    /**
     * @var boolean
     */
    private $contenttype;

    /**
     * @var string
     */
    private $content;

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
     * Set contentname
     *
     * @param string $contentname
     * @return Lessoncontents
     */
    public function setContentname($contentname)
    {
        $this->contentname = $contentname;

        return $this;
    }

    /**
     * Get contentname
     *
     * @return string 
     */
    public function getContentname()
    {
        return $this->contentname;
    }

    /**
     * Set contenttype
     *
     * @param boolean $contenttype
     * @return Lessoncontents
     */
    public function setContenttype($contenttype)
    {
        $this->contenttype = $contenttype;

        return $this;
    }

    /**
     * Get contenttype
     *
     * @return boolean 
     */
    public function getContenttype()
    {
        return $this->contenttype;
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
}
