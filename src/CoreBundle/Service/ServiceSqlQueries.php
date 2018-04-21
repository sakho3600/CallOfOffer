<?php
/**
 * Created by PhpStorm.
 * User: Phil
 * Date: 25/01/2018
 * Time: 11:34
 */

namespace CoreBundle\Service;


use CoreBundle\Entity\Proposition;

class ServiceSqlQueries
{
    private $em;

    public function getRow($id, $entity)
    {
        $row = $this->em
            ->getRepository('CoreBundle:' . $entity)
            ->find($id);
        return $row;

    }

    public function listAll($entity)
    {
        $rows = $this->em
            ->getRepository('CoreBundle:' . $entity)
            ->findAll();
        return $rows;

    }

    public function edit()
    {
        $this->em->flush();
        return true;
    }

    public function add($row)
    {
        $this->em->persist($row);
        $this->em->flush();
        return true;
    }

    public function delete($id, $entity)
    {
        $row = $this->getRow($id, $entity);
        $this->em->remove($row);
        $this->em->flush();

        return true;
    }

    public function truncate($table)
    {
        $connection = $this->em->getConnection();

        $connection->query('START TRANSACTION;SET FOREIGN_KEY_CHECKS=0');
        $platform = $connection->getDatabasePlatform();
        $connection->executeUpdate($platform->getTruncateTableSQL($table, true));
        $connection->query('START TRANSACTION;SET FOREIGN_KEY_CHECKS=1');
    }

    public function __construct($doctrine)
    {
        $this->em = $doctrine->getManager();
    }
}