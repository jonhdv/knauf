<?php

declare(strict_types=1);

namespace App\Controller;

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
use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractRenderController
{

    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        Environment $template,
        UserRepository $userRepository,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        UserPasswordEncoderInterface $passwordEncoder,
        SessionInterface $session,
        EntityManagerInterface $entityManager,

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
    }

    public function index(Request $httpRequest): Response
    {
        $form = $this->formFactory->create(UserSignupType::class)
            ->add('reset', SubmitType::class, ['label' => 'Registrarse', 'attr' => ['class' => 'btn btn-primary btn-block']]);

        $form->handleRequest($httpRequest);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $user
                ->setInitialRole()
                ->setEnabled(false);
            ;

                $this->entityManager->persist($user);
                $this->entityManager->flush();


                $this->session->getFlashBag()->add(
                    'success',
                    'Hemos enviado un email a tu dirección para confirmar la cuenta'
                );

            return new RedirectResponse($this->router->generate('index'));
        }

        return $this->render('index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}