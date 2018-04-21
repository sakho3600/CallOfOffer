<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Proposition
 *
 * @ORM\Table(name="proposition")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\PropositionRepository")
 */
class Proposition
{
    /**
     * @ORM\Id
     * @ORM\Column(name="proposition_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\Provider", inversedBy="propositions")
     * @ORM\JoinColumn(name="provider_id", referencedColumnName="id")
     */
    private $provider;

   /**
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\CallOfOffer", inversedBy="propositions")
     * @ORM\JoinColumn(name="call_of_offer_id", referencedColumnName="id")
     */
    private $callOfOffer;


    /**
     * @var float
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;



    /**
     * Set price
     *
     * @param string $price
     *
     * @return Proposition
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set provider
     *
     * @param integer $provider
     *
     * @return Proposition
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get provider
     *
     * @return integer
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Set callOfOffer
     *
     * @param integer $callOfOffer
     *
     * @return Proposition
     */
    public function setCallOfOffer($callOfOffer)
    {
        $this->callOfOffer = $callOfOffer;

        return $this;
    }

    /**
     * Get callOfOffer
     *
     * @return integer
     */
    public function getCallOfOffer()
    {
        return $this->callOfOffer;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Proposition
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
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
}