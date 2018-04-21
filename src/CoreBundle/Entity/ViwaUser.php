<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ViwaUser
 *
 * @ORM\Table(name="viwa_user")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\ViwaUserRepository")
 */
class ViwaUser extends User
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        $this->addRole('ROLE_VIWAMETAL');
    }

}
