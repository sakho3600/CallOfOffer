<?php

namespace CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="provider")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\ProviderRepository")
 */
class Provider extends User
{
    /**
     * @ORM\Id
     * @ORM\Column(name="provider_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="CoreBundle\Entity\Proposition", mappedBy="provider")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $propositions;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->addRole('ROLE_PROVIDER');
        $this->propositions = new ArrayCollection();
    }



    /**
     * Add proposition
     *
     * @param \CoreBundle\Entity\Proposition $proposition
     *
     * @return Provider
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
