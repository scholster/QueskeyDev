<?php

namespace Queskey\FrontEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Course
 */
class Course
{
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
     * @var integer
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
     */
    private $id;

    /**
     * @var \Queskey\FrontEndBundle\Entity\User
=======
>>>>>>> 5ae0d2ff7e0fcb46dc6dba8d9e9e1dd0ebcdd7a4
=======
>>>>>>> 5ae0d2ff7e0fcb46dc6dba8d9e9e1dd0ebcdd7a4
=======
>>>>>>> 5ae0d2ff7e0fcb46dc6dba8d9e9e1dd0ebcdd7a4
=======
>>>>>>> 5ae0d2ff7e0fcb46dc6dba8d9e9e1dd0ebcdd7a4
     */
    private $id;

    /**
     * @var \Queskey\FrontEndBundle\Entity\SubCategory
     */
    private $subcat;

    /**
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 5ae0d2ff7e0fcb46dc6dba8d9e9e1dd0ebcdd7a4
=======
>>>>>>> 5ae0d2ff7e0fcb46dc6dba8d9e9e1dd0ebcdd7a4
=======
>>>>>>> 5ae0d2ff7e0fcb46dc6dba8d9e9e1dd0ebcdd7a4
     * @var \Queskey\FrontEndBundle\Entity\User
     */
    private $instructor;


    /**
>>>>>>> 5ae0d2ff7e0fcb46dc6dba8d9e9e1dd0ebcdd7a4
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
     * Get id
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set instructor
=======
>>>>>>> 5ae0d2ff7e0fcb46dc6dba8d9e9e1dd0ebcdd7a4
=======
>>>>>>> 5ae0d2ff7e0fcb46dc6dba8d9e9e1dd0ebcdd7a4
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
<<<<<<< HEAD
=======
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
=======
>>>>>>> 5ae0d2ff7e0fcb46dc6dba8d9e9e1dd0ebcdd7a4
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
<<<<<<< HEAD
>>>>>>> 5ae0d2ff7e0fcb46dc6dba8d9e9e1dd0ebcdd7a4
    }

    /**
     * Set instructor
=======
>>>>>>> 5ae0d2ff7e0fcb46dc6dba8d9e9e1dd0ebcdd7a4
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
=======
>>>>>>> 5ae0d2ff7e0fcb46dc6dba8d9e9e1dd0ebcdd7a4
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
}
