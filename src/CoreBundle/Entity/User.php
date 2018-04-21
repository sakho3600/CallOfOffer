<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\UserRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="integer")
 * @ORM\DiscriminatorMap({1 = "ViwaUser", 2 = "Provider" })
 *
 * @ORM\AttributeOverrides({
 *     @ORM\AttributeOverride(name="email",column=@ORM\Column(name="email", type="string", length=255, unique=false, nullable=true)),
 *
 *     @ORM\AttributeOverride(name="emailCanonical",column=@ORM\Column(name="emailCanonical", type="string", length=255, unique=false, nullable=true)),
 *
 *     @ORM\AttributeOverride(name="usernameCanonical",column=@ORM\Column(name="usernameCanonical", type="string", length=255, unique=false, nullable=true))
 * })
 */
abstract class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

}

