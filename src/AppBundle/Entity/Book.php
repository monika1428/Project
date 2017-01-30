<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Book
 *
 * @ORM\Table(name="book")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookRepository")
 */
class Book
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="userId", type="integer")
     */
    private $userId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="bookDate", type="date")
     */
    private $bookDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="executionDate", type="date", nullable=true)
     */
    private $executionDate;

    /**
     * @var int
     *
     * @ORM\Column(name="deliveryId", type="integer")
     */
    private $deliveryId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="remitDate", type="date", nullable=true)
     */
    private $remitDate;

    /**
     * @var float
     *
     * @ORM\Column(name="cost", type="float")
     */
    private $cost;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Book
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set bookDate
     *
     * @param \DateTime $bookDate
     *
     * @return Book
     */
    public function setBookDate($bookDate)
    {
        $this->bookDate = $bookDate;

        return $this;
    }

    /**
     * Get bookDate
     *
     * @return \DateTime
     */
    public function getBookDate()
    {
        return $this->bookDate;
    }

    /**
     * Set executionDate
     *
     * @param \DateTime $executionDate
     *
     * @return Book
     */
    public function setExecutionDate($executionDate)
    {
        $this->executionDate = $executionDate;

        return $this;
    }

    /**
     * Get executionDate
     *
     * @return \DateTime
     */
    public function getExecutionDate()
    {
        return $this->executionDate;
    }

    /**
     * Set deliveryId
     *
     * @param integer $deliveryId
     *
     * @return Book
     */
    public function setDeliveryId($deliveryId)
    {
        $this->deliveryId = $deliveryId;

        return $this;
    }

    /**
     * Get deliveryId
     *
     * @return int
     */
    public function getDeliveryId()
    {
        return $this->deliveryId;
    }

    /**
     * Set remitDate
     *
     * @param \DateTime $remitDate
     *
     * @return Book
     */
    public function setRemitDate($remitDate)
    {
        $this->remitDate = $remitDate;

        return $this;
    }

    /**
     * Get remitDate
     *
     * @return \DateTime
     */
    public function getRemitDate()
    {
        return $this->remitDate;
    }

    /**
     * Set cost
     *
     * @param float $cost
     *
     * @return Book
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }
}

