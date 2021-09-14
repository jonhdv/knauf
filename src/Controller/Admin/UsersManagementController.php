<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AbstractRenderController;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class UsersManagementController extends AbstractRenderController
{
    private EntityManagerInterface $entityManager;
    private RouterInterface $router;

    public function __construct(Environment $template, RouterInterface $router, EntityManagerInterface $entityManager) {
        parent::__construct($template);
        $this->entityManager = $entityManager;
        $this->router = $router;
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

    public function enable(int $idUser): Response {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $idUser]);

        if (empty($user)) {
            return new JsonResponse('No se pudo encontrar la formación', Response::HTTP_BAD_REQUEST);
        }

        $user->setEnabled(true);
        $this->entityManager->flush();

        return new RedirectResponse($this->router->generate('admin_users_management'));
    }

    public function disable(int $idUser): Response {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $idUser]);

        if (empty($user)) {
            return new JsonResponse('No se pudo encontrar la formación', Response::HTTP_BAD_REQUEST);
        }

        $user->setEnabled(false);
        $this->entityManager->flush();

        return new RedirectResponse($this->router->generate('admin_users_management'));
    }
}