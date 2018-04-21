<?php

namespace CoreBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CallOfOffer
 *
 * @ORM\Table(name="call_of_offer")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\CallOfOfferRepository")
 */
class CallOfOffer
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
     * @var string
     *
     * @ORM\Column(name="tag", type="string", length=100)
     */
    private $tag;

    /**
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     */
    private $quantity;


    /**
     * @ORM\OneToMany(targetEntity="Proposition", cascade={"remove"}, mappedBy="callOfOffer")
     * @ORM\JoinColumn(name="proposition_id", referencedColumnName="id")
     * @ORM\JoinColumn(nullable=true)
     */
    private $propositions;

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
     * Set tag
     *
     * @param string $tag
     *
     * @return CallOfOffer
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->propositions = new ArrayCollection();
        $this->date = new DateTime();
    }


    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return CallOfOffer
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return CallOfOffer
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Add proposition
     *
     * @param \CoreBundle\Entity\Proposition $proposition
     *
     * @return CallOfOffer
     */
    public function addProposition(\CoreBundle\Entity\Proposition $proposition)
    {
        $this->propositions[] = $proposition;

        return $this;
    }

    /**
     * Remove proposition
     *
     * @param \CoreBundle\Entity\Proposition $proposition
     */
    public function removeProposition(\CoreBundle\Entity\Proposition $proposition)
    {
        $this->propositions->removeElement($proposition);
    }

    /**
     * Get propositions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPropositions()
    {
        return $this->propositions;
    }
}
