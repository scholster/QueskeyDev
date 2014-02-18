<?php

namespace Queskey\FrontEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Course
 */
class Course
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var boolean
     */
    private $published;

    /**
     * @var \Queskey\FrontEndBundle\Entity\User
     */
    private $instructor;

    /**
     * @var \Queskey\FrontEndBundle\Entity\SubCategory
     */
    private $subcat;


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
     * Set name
     *
     * @param string $name
     * @return Course
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Course
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set published
     *
     * @param boolean $published
     * @return Course
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean 
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set instructor
     *
     * @param \Queskey\FrontEndBundle\Entity\User $instructor
     * @return Course
     */
    public function setInstructor(\Queskey\FrontEndBundle\Entity\User $instructor = null)
    {
        $this->instructor = $instructor;

        return $this;
    }

    /**
     * Get instructor
     *
     * @return \Queskey\FrontEndBundle\Entity\User 
     */
    public function getInstructor()
    {
        return $this->instructor;
    }

    /**
     * Set subcat
     *
     * @param \Queskey\FrontEndBundle\Entity\SubCategory $subcat
     * @return Course
     */
    public function setSubcat(\Queskey\FrontEndBundle\Entity\SubCategory $subcat = null)
    {
        $this->subcat = $subcat;

        return $this;
    }

    /**
     * Get subcat
     *
     * @return \Queskey\FrontEndBundle\Entity\SubCategory 
     */
    public function getSubcat()
    {
        return $this->subcat;
    }
}
