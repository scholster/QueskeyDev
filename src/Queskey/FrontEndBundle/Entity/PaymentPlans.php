<?php

namespace Queskey\FrontEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PaymentPlans
 */
class PaymentPlans
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     */
    private $price;

    /**
     * @var integer
     */
    private $expirytime;

    /**
     * @var float
     */
    private $discountPercent;

    /**
     * @var float
     */
    private $resubscriptionPrice;

    /**
     * @var string
     */
    private $description;


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
     * Set price
     *
     * @param float $price
     * @return PaymentPlans
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set expirytime
     *
     * @param integer $expirytime
     * @return PaymentPlans
     */
    public function setExpirytime($expirytime)
    {
        $this->expirytime = $expirytime;

        return $this;
    }

    /**
     * Get expirytime
     *
     * @return integer 
     */
    public function getExpirytime()
    {
        return $this->expirytime;
    }

    /**
     * Set discountPercent
     *
     * @param float $discountPercent
     * @return PaymentPlans
     */
    public function setDiscountPercent($discountPercent)
    {
        $this->discountPercent = $discountPercent;

        return $this;
    }

    /**
     * Get discountPercent
     *
     * @return float 
     */
    public function getDiscountPercent()
    {
        return $this->discountPercent;
    }

    /**
     * Set resubscriptionPrice
     *
     * @param float $resubscriptionPrice
     * @return PaymentPlans
     */
    public function setResubscriptionPrice($resubscriptionPrice)
    {
        $this->resubscriptionPrice = $resubscriptionPrice;

        return $this;
    }

    /**
     * Get resubscriptionPrice
     *
     * @return float 
     */
    public function getResubscriptionPrice()
    {
        return $this->resubscriptionPrice;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return PaymentPlans
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
}
