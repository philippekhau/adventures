<?php

namespace App\Service;

use App\Entity\Adventure;
use App\Entity\Log;
use Doctrine\Persistence\ManagerRegistry;

class LogService
{
    /**
     * @param ManagerRegistry $doctrine
     */
    public function __construct(
        private ManagerRegistry $doctrine,
    )
    {
    }

    /**
     * @param Adventure $adventure
     * @param string $message
     * @return Log
     */
    public function make(Adventure $adventure, string $message): Log
    {
        $log = new Log();
        $log
            ->setAdventure($adventure)
            ->setMessage($message);

        return $log;
    }

    /**
     * @param Adventure $adventure
     * @param string $message
     * @return Log
     */
    public function create(Adventure $adventure, string $message): Log
    {
        $log = $this->make($adventure, $message);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($log);
        $entityManager->flush();

        return $log;
    }
}
