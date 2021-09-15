<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Block;
use App\Entity\Training;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class BlockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Block::class);
    }

    public function getTrainingBlocks(array $arrayBloks): array
    {
        $queryBuilder = $this->createQueryBuilder('b')
            ->select('b')
            ->andWhere('b.id IN (:blocks)')
            ->setParameter('blocks', $arrayBloks);

        return $queryBuilder->getQuery()->getArrayResult();
    }

    public function getTrainingTime(array $arrayBloks): ?string
    {
        $queryBuilder = $this->createQueryBuilder('b')
            ->select('sum(b.time)')
            ->andWhere('b.id IN (:blocks)')
            ->setParameter('blocks', $arrayBloks);

        try {
            return $queryBuilder->getQuery()->getSingleScalarResult();
        } catch (NoResultException $e) {
            return null;
        }
    }
}
