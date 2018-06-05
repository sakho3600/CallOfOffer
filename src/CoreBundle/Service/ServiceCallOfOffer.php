<?php
/**
 * Created by PhpStorm.
 * User: Phil
 * Date: 28/04/2018
 * Time: 11:20
 */

namespace CoreBundle\Service;


class ServiceCallOfOffer
{
    private $em;

////////////////////////////
    public function acceptProposition($idProp, $comment)
    {
        $repProp = $this->em->getRepository('CoreBundle:Proposition');
        $prop = $repProp->find($idProp);
        $prop->setIsRefused(false);
        $prop->setIsAccepted(true);
        $prop->setResponseViwametal($comment);
        $coo = $repProp->getCooByPropositionId($idProp);
        $coo->setInProgress(false);
        $this->em->flush();
        return $prop;
    }

    public function refuseProposition($idProp, $comment)
    {
        $repProp = $this->em->getRepository('CoreBundle:Proposition');
        $prop = $repProp->find($idProp);
        $prop->setIsRefused(true);
        $prop->setIsAccepted(false);
        $prop->setResponseViwametal($comment);
        $this->em->flush();
        return $prop;
    }


    public function __construct($doctrine)
    {
        $this->em = $doctrine->getManager();
    }
}