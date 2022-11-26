<?php

namespace App\Repository;

use App\Entity\Adventure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Adventure>
 *
 * @method Adventure|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adventure|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adventure[]    findAll()
 * @method Adventure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdventureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adventure::class);
    }

    public function save(Adventure $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Adventure $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function eagerFind(int $id, array $relations = []): ?Adventure
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.id = :id')
            ->setParameter('id', $id);

        foreach ($relations as $relation) {
            switch ($relation) {
                case 'character':
                    $qb->innerJoin('a.character', 'c')
                        ->addSelect('c');
                    break;
                case 'tile.monster':
                case 'tile':
                    $qb->innerJoin('a.tile', 't')
                        ->addSelect('t')
                        ->andWhere('t.active = :active')
                        ->setParameter('active', true);

                    if ($relation === 'tile.monster') {
                        $qb->innerJoin('t.monster', 'm')
                            ->addSelect('m');
                    }
                    break;
                case 'logs':
                    $qb->leftJoin('a.logs', 'l')
                        ->addSelect('l');
            }
        }

        return $qb->getQuery()->getOneOrNullResult();
    }
}
