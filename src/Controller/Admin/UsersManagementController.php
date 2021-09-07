<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AbstractRenderController;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

class UsersManagementController extends AbstractRenderController
{
    private EntityManagerInterface $entityManager;

    public function __construct(Environment $template, EntityManagerInterface $entityManager) {
        parent::__construct($template);
        $this->entityManager = $entityManager;
    }

    public function list(Request $httpRequest): Response
    {
        $userRepository = $this->entityManager->getRepository(User::class);

        $queryBuilder = $userRepository->createQueryBuilder('u')
            ->select('u')
            ->where('u.roles = :roles')
            ->setParameter('roles', '["ROLE_USER"]');

        $users = $queryBuilder->getQuery()->getResult();

        return $this->render('admin/users-management.html.twig', [
            'users' => $users
        ]);
    }
}