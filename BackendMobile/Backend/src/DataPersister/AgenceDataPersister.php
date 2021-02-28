<?php
// src/DataPersister/AgenceDataPersister.php

namespace App\DataPersister;

use App\Entity\Agence;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

/**
 *
 */

class AgenceDataPersister implements ContextAwareDataPersisterInterface
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
        return $data instanceof Agence;
    }

    /**
     * @param Agence $data
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
        $data->setIsDeleted(true);
        $this->_entityManager->persist($data);
        $users = $data->getUsers();
        foreach ($users as $user) {
            $archiveUser = $user->getStatut(true);
            $this->_entityManager-> persist($archiveUser);
        }
        //$this->_entityManager->remove($data);
        $this->_entityManager->flush();
    }
}