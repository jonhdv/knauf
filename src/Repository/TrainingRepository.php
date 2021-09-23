<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Training;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class TrainingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Training::class);
    }

    public function findByCriteria(array $pagination, string $search): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->join('t.user', 'u')
            ->orderBy('t.updatedAt', 'DESC')
        ;

        if (!empty($search)) {
            $queryBuilder->where('u.name LIKE :name or u.companyName LIKE :companyName')
                ->setParameter('name', "%{$search}%")
                ->setParameter('companyName', "%{$search}%")
            ;
        }

        $doctrineQuery = $queryBuilder->getQuery()
            ->setFirstResult($pagination['maxResults'] * ($pagination['page'] - 1))
            ->setMaxResults($pagination['maxResults']);

        return new Paginator($doctrineQuery);
    }
}
