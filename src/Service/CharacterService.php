<?php

namespace App\Service;

use App\Entity\Adventure;
use App\Entity\Character;
use App\Entity\Dragon;
use App\Entity\Tile;
use App\Exception\ActionNotAllowedException;
use App\Exception\AdventureOverException;
use App\Exception\CharacterDeadException;
use App\Repository\AdventureRepository;
use App\Repository\TileRepository;
use Doctrine\Persistence\ManagerRegistry;

class CharacterService
{
    /**
     * @param ManagerRegistry $doctrine
     * @param AdventureRepository $adventureRepository
     * @param TileRepository $tileRepository
     * @param MonsterService $monsterService
     * @param TileService $tileService
     * @param LogService $logService
     */
    public function __construct(
        private ManagerRegistry     $doctrine,
        private AdventureRepository $adventureRepository,
        private TileRepository      $tileRepository,
        private MonsterService      $monsterService,
        private TileService         $tileService,
        private LogService          $logService
    )
    {
    }

    /**
     * @param Adventure $adventure
     * @return Character
     */
    public function make(Adventure $adventure): Character
    {
        $character = new Character();
        $character->setAdventure($adventure);

        return $character;
    }

    /**
     * @param Adventure $adventure
     * @return Character
     */
    public function create(Adventure $adventure): Character
    {
        $character = $this->make($adventure);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($character);
        $entityManager->flush();

        return $character;
    }

    /**
     * @param Character $character
     * @return Character
     * @throws CharacterDeadException|ActionNotAllowedException
     */
    public function move(Character $character): Character
    {
        $entityManager = $this->doctrine->getManager();

        $adventure = $this->adventureRepository->eagerFind($character->getAdventure()->getId(), ['tile.monster']);

        $this->checkCharacterAction($character, $adventure, 'move');

        /** @var Tile $tile */
        $tile = $adventure->getTile()->first();
        $monster = $tile->getMonster();

        if ($monster->isAlive()) {
            $this->monsterService->attack($monster, $character);

            if (!$tile->allowMovement($character)) {
                $this->logService->create($tile->getAdventure(), 'Character tries to move. Action not allowed.');
                throw new ActionNotAllowedException();
            }
        }

        $this->computeTileHpMalus($character, $tile);

        $tile->setActive(false);

        if ($character->isResting()) {
            $character->setResting(false);
        }

        $adventure->setScore($adventure->getScore() + 10);
        $entityManager->flush();

        $boss = $this->tileRepository->countAdventureTiles($adventure) === 10;

        $newTile = $this->tileService->create($adventure, true, $boss);

        $this->logService->create($adventure, 'Character moved to tile #' . $newTile->getId());

        return $character;
    }

    /**
     * @param Character $character
     * @return Character
     * @throws ActionNotAllowedException|CharacterDeadException
     */
    public function attack(Character $character): Character
    {
        $entityManager = $this->doctrine->getManager();

        $adventure = $this->adventureRepository->eagerFind($character->getAdventure()->getId(), ['tile.monster']);

        $this->checkCharacterAction($character, $adventure, 'attack');

        /** @var Tile $tile */
        $tile = $adventure->getTile()->first();

        $monster = $tile->getMonster();

        if (!$monster->isAlive()) {
            throw new ActionNotAllowedException();
        }

        $attack = $character->getAttack() + $tile->attackBonus($character);
        if ($attack > $monster->getArmor()) {
            $monster->setHp(max(0, $monster->getHp() - ($attack - $monster->getArmor())));
            $entityManager->flush();
            $this->logService->create($tile->getAdventure(), 'Character attacked ' . ucfirst($monster->getType()) . '. Attack Power: ' . $attack);
        }

        if ($monster->isAlive()) {
            $this->monsterService->attack($monster, $character);
        } else {
            $this->logService->create($tile->getAdventure(), 'Monster ' . ucfirst($monster->getType()) . ' is dead.');
            $scoreBonus = $monster instanceof Dragon ? 20 : 5;
            $adventure->setScore($adventure->getScore() + $scoreBonus);
            $entityManager->flush();
        }

        $this->computeTileHpMalus($character, $tile);

        return $character;
    }

    /**
     * @param Character $character
     * @return Character
     * @throws ActionNotAllowedException|CharacterDeadException
     */
    public function rest(Character $character): Character
    {
        $entityManager = $this->doctrine->getManager();

        $adventure = $this->adventureRepository->eagerFind($character->getAdventure()->getId(), ['tile.monster']);

        $this->checkCharacterAction($character, $adventure, 'rest');

        /** @var Tile $tile */
        $tile = $adventure->getTile()->first();

        $monster = $tile->getMonster();

        if ($monster->isAlive() || $character->isResting()) {
            throw new ActionNotAllowedException();
        }

        $character
            ->setResting(true)
            ->setHp($character->getHp() + 2);
        $entityManager->flush();
        $this->logService->create($tile->getAdventure(), 'Character is resting. New HP Value: ' . $character->getHp());

        $this->computeTileHpMalus($character, $tile);

        return $character;
    }

    /**
     * @param Character $character
     * @param Adventure $adventure
     * @param string $action
     * @return void
     * @throws AdventureOverException
     * @throws CharacterDeadException|ActionNotAllowedException
     */
    protected function checkCharacterAction(Character $character, Adventure $adventure, string $action)
    {
        $monster = $adventure->getTile()->first()->getMonster();

        if ($monster instanceof Dragon) {
            if (!$monster->isAlive()) {
                throw new AdventureOverException();
            }

            if (in_array($action, ['rest', 'move'])) {
                throw new ActionNotAllowedException();
            }
        }

        if (!$character->isAlive()) {
            throw new CharacterDeadException();
        }
    }

    /**
     * @param Character $character
     * @param Tile $tile
     * @return bool
     * @throws CharacterDeadException
     */
    protected function computeTileHpMalus(Character $character, Tile $tile): bool
    {
        $entityManager = $this->doctrine->getManager();

        $malus = $tile->hpMalus($character);
        if ($malus) {
            $character->setHp($character->getHp() - $malus);
            $entityManager->flush();

            $this->logService->create($tile->getAdventure(), 'Character took ' . $malus . 'HP malus from ' . $tile->getType() . '. New HP Value: ' . $character->getHp());

            if (!$character->isAlive()) {
                throw new CharacterDeadException();
            }

            return true;
        }

        return false;
    }
}
