<?php

declare(strict_types=1);

namespace App\Controller\Users;

use App\Controller\AbstractRenderController;
use App\Entity\Block;
use App\Entity\Training;
use App\Entity\User;
use App\Form\UserSignupType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    private SessionInterface $session;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private Security $security;
    private RouterInterface $router;
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

    public function studio(Request $httpRequest): Response
    {
        $studio = $this->security->getUser();

        return $this->render('users/studio.html.twig', [
            'studio' => $studio
        ]);
    }

    public function blocks(Request $httpRequest): Response
    {
        $blocks = $this->entityManager->getRepository(Block::class)->findAll();

        return $this->render('users/blocks.html.twig', [
            'blocks' => $blocks
        ]);
    }

    public function date(Request $httpRequest): Response
    {
        return $this->render('users/date.html.twig');
    }

    public function updateStudio (Request $httpRequest): Response {
        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['id' => $this->security->getUser()->getId()]);
        ;
        if (empty($user)) {
            return new JsonResponse('No se pudo encontrar el usuario', Response::HTTP_BAD_REQUEST);
        }

        $user->setCompanyName($httpRequest->request->get('companyName', ''));
        $user->setAddress($httpRequest->request->get('address', ''));
        $user->setCountry($httpRequest->request->get('country', ''));
        $user->setCity($httpRequest->request->get('city', ''));
        $user->setPostalCode($httpRequest->request->get('postalCode', ''));
        $user->setMunicipality($httpRequest->request->get('municipality', ''));
        $user->setName($httpRequest->request->get('name', ''));
        $user->setPhone($httpRequest->request->get('phone', ''));
        $user->setEmail($httpRequest->request->get('email', ''));
        $user->setCommentary($httpRequest->request->get('commentary'));

        $training = $this->session->get('training');

        if ($training === null) {
            $training = new Training($user);
            $training->setStudioConfirmed(true);

            $this->entityManager->persist($training);


        } else {
            $training = $this->entityManager->getRepository(Training::class)
                ->findOneBy(['id' => $training->getId()]);
            ;
            $training->setStudioConfirmed(true);
        }

        $this->session->set('training', $training);
        $this->entityManager->flush();

        return new RedirectResponse($this->router->generate('users_training_blocks'));
    }

    public function updateBlocks(Request $httpRequest): Response {
        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['id' => $this->security->getUser()->getId()]);
        ;
        if (empty($user)) {
            return new JsonResponse('No se pudo encontrar el usuario', Response::HTTP_BAD_REQUEST);
        }

        $training = $this->session->get('training');
        $blocks = array_keys($httpRequest->request->get('block'));

        if ($training === null) {
            $training = new Training($user);
            $training->setBlocks($blocks);

            $this->entityManager->persist($training);
        } else {
            $training = $this->entityManager->getRepository(Training::class)
                ->findOneBy(['id' => $training->getId()]);
            ;
            $training->setBlocks($blocks);
        }

        $this->session->set('training', $training);
        $this->entityManager->flush();

        return new RedirectResponse($this->router->generate('users_training_date'));
    }

    public function updateDate(Request $httpRequest): Response {
        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['id' => $this->security->getUser()->getId()]);
        ;
        if (empty($user)) {
            return new JsonResponse('No se pudo encontrar el usuario', Response::HTTP_BAD_REQUEST);
        }

        $training = $this->session->get('training');
        $date = $httpRequest->request->get('datetime');

        if (!empty($date)) {
            $date = \DateTime::createFromFormat('Y-m-d\TH:i', $date);

            if (!$date) {
                return new JsonResponse('Fecha no valida', Response::HTTP_BAD_REQUEST);
            }
        }

        if ($training === null) {
            $training = new Training($user);
            $training->setDatetime($date);

            $this->entityManager->persist($training);
        } else {
            $training = $this->entityManager->getRepository(Training::class)
                ->findOneBy(['id' => $training->getId()]);
            ;
            $training->setDatetime($date);
        }

        $this->session->set('training', $training);
        $this->entityManager->flush();

        return new RedirectResponse($this->router->generate('users_training_competitors_list'));
    }
}