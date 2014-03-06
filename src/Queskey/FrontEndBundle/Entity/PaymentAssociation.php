<?php

namespace Queskey\FrontEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PaymentAssociation
 */
class PaymentAssociation
{
    /**
     * @var boolean
     */
    private $allLimit;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Queskey\FrontEndBundle\Entity\Course
     */
    private $course;

    /**
     * @var \Queskey\FrontEndBundle\Entity\PaymentPlans
     */
    private $paymentplan;


    /**
     * Set allLimit
     *
     * @param boolean $allLimit
     * @return PaymentAssociation
     */
    public function setAllLimit($allLimit)
    {
        $this->allLimit = $allLimit;

        return $this;
    }

    /**
     * Get allLimit
     *
     * @return boolean 
     */
    public function getAllLimit()
    {
        return $this->allLimit;
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
     * Set course
     *
     * @param \Queskey\FrontEndBundle\Entity\Course $course
     * @return PaymentAssociation
     */
    public function setCourse(\Queskey\FrontEndBundle\Entity\Course $course = null)
    {
        $this->course = $course;

        return $this;
    }

    /**
     * Get course
     *
     * @return \Queskey\FrontEndBundle\Entity\Course 
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * Set paymentplan
     *
     * @param \Queskey\FrontEndBundle\Entity\PaymentPlans $paymentplan
     * @return PaymentAssociation
     */
    public function setPaymentplan(\Queskey\FrontEndBundle\Entity\PaymentPlans $paymentplan = null)
    {
        $this->paymentplan = $paymentplan;

        return $this;
    }

    /**
     * Get paymentplan
     *
     * @return \Queskey\FrontEndBundle\Entity\PaymentPlans 
     */
    public function getPaymentplan()
    {
        return $this->paymentplan;
    }
}
