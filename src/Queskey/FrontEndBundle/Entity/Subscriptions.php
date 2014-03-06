<?php

namespace Queskey\FrontEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subscriptions
 */
class Subscriptions
{
    /**
     * @var \DateTime
     */
    private $joiningtime;

    /**
     * @var \DateTime
     */
    private $expirytime;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Queskey\FrontEndBundle\Entity\PaymentPlans
     */
    private $paymentplanid;

    /**
     * @var \Queskey\FrontEndBundle\Entity\Course
     */
    private $courseid;

    /**
     * @var \Queskey\FrontEndBundle\Entity\User
     */
    private $userid;


    /**
     * Set joiningtime
     *
     * @param \DateTime $joiningtime
     * @return Subscriptions
     */
    public function setJoiningtime($joiningtime)
    {
        $this->joiningtime = $joiningtime;

        return $this;
    }

    /**
     * Get joiningtime
     *
     * @return \DateTime 
     */
    public function getJoiningtime()
    {
        return $this->joiningtime;
    }

    /**
     * Set expirytime
     *
     * @param \DateTime $expirytime
     * @return Subscriptions
     */
    public function setExpirytime($expirytime)
    {
        $this->expirytime = $expirytime;

        return $this;
    }

    /**
     * Get expirytime
     *
     * @return \DateTime 
     */
    public function getExpirytime()
    {
        return $this->expirytime;
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
     * Set paymentplanid
     *
     * @param \Queskey\FrontEndBundle\Entity\PaymentPlans $paymentplanid
     * @return Subscriptions
     */
    public function setPaymentplanid(\Queskey\FrontEndBundle\Entity\PaymentPlans $paymentplanid = null)
    {
        $this->paymentplanid = $paymentplanid;

        return $this;
    }

    /**
     * Get paymentplanid
     *
     * @return \Queskey\FrontEndBundle\Entity\PaymentPlans 
     */
    public function getPaymentplanid()
    {
        return $this->paymentplanid;
    }

    /**
     * Set courseid
     *
     * @param \Queskey\FrontEndBundle\Entity\Course $courseid
     * @return Subscriptions
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
     * Set userid
     *
     * @param \Queskey\FrontEndBundle\Entity\User $userid
     * @return Subscriptions
     */
    public function setUserid(\Queskey\FrontEndBundle\Entity\User $userid = null)
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Get userid
     *
     * @return \Queskey\FrontEndBundle\Entity\User 
     */
    public function getUserid()
    {
        return $this->userid;
    }
}
