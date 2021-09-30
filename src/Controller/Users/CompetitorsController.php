<?php

declare(strict_types=1);

namespace App\Controller\Users;

use App\Controller\AbstractRenderController;
use App\Entity\Competitor;
use App\Entity\Training;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
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
    private EntityManagerInterface $entityManager;
    private Security $security;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        Environment $template,
        RouterInterface $router,
        UserPasswordEncoderInterface $passwordEncoder,
        SessionInterface $session,
        EntityManagerInterface $entityManager,
        Security $security
    ) {
        parent::__construct($template);

        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
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

    public function addCompetitors(Request $httpRequest): Response
    {
        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['id' => $this->security->getUser()->getId()]);
        ;
        if (empty($user)) {
            return new JsonResponse('No se pudo encontrar el usuario', Response::HTTP_BAD_REQUEST);
        }

        $training = $this->session->get('training');

        $httpRequest->request->get('competitor') == null ? $competitors = null : $competitors = array_keys($httpRequest->request->get('competitor'));

        if ($training === null) {
            $training = new Training($user);

            $this->entityManager->persist($training);
        } else {
            $training = $this->entityManager->getRepository(Training::class)
                ->findOneBy(['id' => $training->getId()]);
            ;
        }

        $training->setCompetitors(new ArrayCollection());

        if ($competitors != null) {
            foreach ($competitors as $competitor) {
                $result = $this->entityManager->getRepository(Competitor::class)->findOneBy(['id' => $competitor]);
                $training->getCompetitors()->add($result);
            }
        }

        $this->session->set('training', $training);
        $this->entityManager->flush();

        return new RedirectResponse($this->router->generate('users_training_competitors_list'));
    }

    public function createCompetitors(Request $httpRequest): Response
    {
        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['id' => $this->security->getUser()->getId()]);
        ;
        if (empty($user)) {
            return new JsonResponse('No se pudo encontrar el usuario', Response::HTTP_BAD_REQUEST);
        }

        $competitor = new Competitor();

        $competitor->setUser($user);
        $competitor->setEmail($httpRequest->request->get('email'));
        $competitor->setName($httpRequest->request->get('name'));
        $competitor->setSurname($httpRequest->request->get('surname'));
        $competitor->setPosition($httpRequest->request->get('position'));
        $competitor->setFoodIntolerances($httpRequest->request->get('foodIntolerances'));

        $this->entityManager->persist($competitor);
        $this->entityManager->flush();

        return new RedirectResponse($this->router->generate('users_training_competitors_list'));
    }

    public function deleteCompetitors(int $idCompetitor): Response
    {
        $competitor = $this->entityManager->getRepository(Competitor::class)
            ->findOneBy(['id' => $idCompetitor, 'user' => $this->security->getUser()]);
        ;

        if (empty($competitor)) {
            return new JsonResponse('Error al borrar. No se pudo encontrar al participante', Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->remove($competitor);
        $this->entityManager->flush();

        $training = $this->entityManager->getRepository(Training::class)
            ->findOneBy(['id' => $this->session->get('training')->getId()]);
        ;

        $this->session->set('training', $training);

        return new RedirectResponse($this->router->generate('users_training_competitors_list'));
    }
}