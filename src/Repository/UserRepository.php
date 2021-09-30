<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByCriteria(array $pagination, string $search): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.roles = :roles')
            ->setParameter('roles', '["ROLE_USER"]')
            ->orderBy('u.createdAt', 'DESC')
        ;

        if (!empty($search)) {
            $queryBuilder->andWhere('u.name LIKE :name or u.companyName LIKE :companyName')
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
