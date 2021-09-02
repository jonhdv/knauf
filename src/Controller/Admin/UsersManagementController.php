<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AbstractRenderController;
use App\Entity\User;
use App\Form\UserSignupType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;

class UsersManagementController extends AbstractRenderController
{

    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private Security $security;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        Environment $template,
        UserRepository $userRepository,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        UserPasswordEncoderInterface $passwordEncoder,
        SessionInterface $session,
        EntityManagerInterface $entityManager,
        Security $security

    ) {
        parent::__construct($template);

        $this->router = $router;
        $this->userRepository = $userRepository;
        $this->formFactory = $formFactory;
        $this->passwordEncoder = $passwordEncoder;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
        $this->router = $router;
        $this->userRepository = $userRepository;
        $this->session = $session;
        $this->entityManager = $entityManager;
        $this->security = $security;
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