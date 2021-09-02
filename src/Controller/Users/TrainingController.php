<?php

declare(strict_types=1);

namespace App\Controller\Users;

use App\Controller\AbstractRenderController;
use App\Entity\Block;
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

class TrainingController extends AbstractRenderController
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

    public function training(Request $httpRequest): Response
    {
        $blocks = $this->entityManager->getRepository(Block::class)->findAll();

        return $this->render('users/training.html.twig', [
            'blocks' => $blocks
        ]);
    }


}