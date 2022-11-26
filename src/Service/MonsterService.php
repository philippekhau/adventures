<?php

namespace App\Service;

use App\Entity\Character;
use App\Entity\Dragon;
use App\Entity\Ghost;
use App\Entity\Gobelin;
use App\Entity\Monster;
use App\Entity\Ork;
use App\Entity\Troll;
use App\Exception\CharacterDeadException;
use Doctrine\Persistence\ManagerRegistry;

class MonsterService
{
    /**
     * @param ManagerRegistry $doctrine
     * @param LogService $logService
     */
    public function __construct(
        private ManagerRegistry $doctrine,
        private LogService      $logService
    )
    {
    }

    /**
     * @param bool $boss
     * @return Monster
     * @throws \Exception
     */
    public function make(bool $boss = false): Monster
    {
        if ($boss) {
            return new Dragon();
        } else {
            $types = [Ork::class, Gobelin::class, Ghost::class, Troll::class];
            $className = random_int(0, count($types) - 1);

            return new $types[$className];
        }
    }

    /**
     * @param bool $boss
     * @return Monster
     * @throws \Exception
     */
    public function create(bool $boss = false): Monster
    {
        $monster = $this->make($boss);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($monster);
        $entityManager->flush();

        return $monster;
    }

    /**
     * @param Monster $monster
     * @param Character $character
     * @return Monster
     * @throws CharacterDeadException
     */
    public function attack(Monster $monster, Character $character): Monster
    {
        $entityManager = $this->doctrine->getManager();
        $tile = $monster->getTile();

        $attack = $monster->getAttack() + $tile->attackBonus($monster);

        if ($attack > $character->getArmor()) {
            $character->setHp(max(0, $character->getHp() - ($attack - $character->getArmor())));
            $entityManager->flush();
            $this->logService->create($tile->getAdventure(), ucfirst($monster->getType()) . ' #' . $monster->getId() . ' attacked Character for ' . $attack);
        } else {
            $this->logService->create($tile->getAdventure(), 'Character resists ' . ucfirst($monster->getType()) . ' attack. Attack Power: ' . $attack);
        }

        $malus = $tile->hpMalus($monster);
        if ($malus) {
            $monster->setHp($monster->getHp() - $malus);
            $entityManager->flush();
            $this->logService->create($tile->getAdventure(), ucfirst($monster->getType()) . '#' . $monster->getId() . ' took ' . $malus . 'HP damage from environment');
        }

        if (!$character->isAlive()) {
            throw new CharacterDeadException();
        }

        return $monster;
    }
}
