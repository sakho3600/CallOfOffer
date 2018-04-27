<?php

namespace CoreBundle\Repository;

/**
 * PropositionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PropositionRepository extends \Doctrine\ORM\EntityRepository
{

    public function getByCooAndUser($idCoo, $idProvider)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM  CoreBundle:Proposition p WHERE p.provider = :idProvider and p.callOfOffer = :idCoo')
            ->setParameter('idProvider', $idProvider)
            ->setParameter('idCoo', $idCoo)
            ->getSingleResult();
    }

    public function getByUser($idProvider)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM  CoreBundle:Proposition p WHERE p.provider = :idProvider')
            ->setParameter('idProvider', $idProvider)
            ->getSingleResult();
    }
}
