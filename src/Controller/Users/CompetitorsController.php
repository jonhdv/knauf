<?php

declare(strict_types=1);

namespace App\Controller\Users;

use App\Controller\AbstractRenderController;
use App\Entity\Block;
use App\Entity\Competitor;
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

class CompetitorsController extends AbstractRenderController
{
    private SessionInterface $session;
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
        $competitors = $this->entityManager->getRepository(Competitor::class)->findBy(['user' => $this->security->getUser()->getId()]);

        //En caso de que haya presentacion en la sesión, llenamos un array con los ids de los participantes que ya se habían seleccionado
        $seletedcompetitorsIds = [];
        $training = $this->session->get('training');

        if (null !== $training  && null !== $training->getCompetitors()) {
            $seletedcompetitors = $training->getCompetitors()->toArray();

            foreach ($seletedcompetitors as $seletedcompetitor) {
                array_push($seletedcompetitorsIds, $seletedcompetitor->getId());
            }
        }

        return $this->render('users/competitors.html.twig', [
            'competitors' => $competitors,
            'seletedcompetitorsIds' => $seletedcompetitorsIds
        ]);
    }

    public function updateCompetitors(Request $httpRequest): Response {
        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['id' => $this->security->getUser()->getId()]);
        ;
        if (empty($user)) {
            return new JsonResponse('No se pudo encontrar el usuario', Response::HTTP_BAD_REQUEST);
        }

        $training = $this->session->get('training');
        $competitors = array_keys($httpRequest->request->get('competitor'));


        if ($training === null) {
            $training = new Training($user);

            $this->entityManager->persist($training);
        } else {
            $training = $this->entityManager->getRepository(Training::class)
                ->findOneBy(['id' => $training->getId()]);
            ;
        }

        foreach ($competitors as $competitor) {
            $result = $this->entityManager->getRepository(Competitor::class)->findOneBy(['id' => $competitor]);
            $training->addCompetitor($result);
        }

        $this->session->set('training', $training);
        $this->entityManager->flush();

        //return new RedirectResponse($this->router->generate('users_training_competitors_list'));

        $competitors = $this->entityManager->getRepository(Competitor::class)->findBy(['user' => $this->security->getUser()->getId()]);

        //En caso de que haya presentacion en la sesión, llenamos un array con los ids de los participantes que ya se habían seleccionado
        $seletedcompetitorsIds = [];
        $training = $this->session->get('training');

        if (null !== $training  && null !== $training->getCompetitors()) {
            $seletedcompetitors = $training->getCompetitors()->toArray();

            foreach ($seletedcompetitors as $seletedcompetitor) {
                array_push($seletedcompetitorsIds, $seletedcompetitor->getId());
            }
        }

        return $this->render('users/competitors.html.twig', [
            'competitors' => $competitors,
            'seletedcompetitorsIds' => $seletedcompetitorsIds
        ]);
    }
}