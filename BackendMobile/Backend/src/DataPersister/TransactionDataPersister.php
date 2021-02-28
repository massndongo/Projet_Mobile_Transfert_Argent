<?php
// src/DataPersister/TransactionDataPersister.php

namespace App\DataPersister;

use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

/**
 *
 */

class TransactionDataPersister implements ContextAwareDataPersisterInterface
{
    private $_entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->_entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Transaction;
    }

    /**
     * @param Transaction $data
     */
    public function persist($data, array $context = [])
    {
        $this->_entityManager->persist($data);
        $this->_entityManager->flush();

       return $data;
    }


    /**
     * {@inheritdoc}
     */
    
    public function remove($data, array $context = [])
    {
        if (empty($data)) {
        }
        $data->setStatut(true);
        $this->_entityManager->persist($data);
        //$this->_entityManager->remove($data);
        $this->_entityManager->flush();
    }
}