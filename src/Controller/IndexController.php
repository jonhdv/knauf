<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserSignupType;
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
    private EntityManagerInterface $entityManager;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        Environment $template,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        UserPasswordEncoderInterface $passwordEncoder,
        SessionInterface $session,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($template);
        $this->router = $router;
        $this->formFactory = $formFactory;
        $this->passwordEncoder = $passwordEncoder;
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
                ->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()))
                ->setEnabled(true);
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