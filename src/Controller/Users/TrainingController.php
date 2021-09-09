<?php

declare(strict_types=1);

namespace App\Controller\Users;

use App\Controller\AbstractRenderController;
use App\Entity\Block;
use App\Entity\Training;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
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
    private EntityManagerInterface $entityManager;
    private Security $security;
    private RouterInterface $router;

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
        $this->session = $session;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function list(): Response
    {
        $user = $this->security->getUser();

        $trainings = $this->entityManager->getRepository(Training::class)->findBy(['user' => $user->getId()], ['updatedAt' => 'DESC']);

        return $this->render('users/training-list.html.twig', [
            'trainings' => $trainings
        ]);
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

        $date = $httpRequest->request->get('date');
        $time = $httpRequest->request->get('time');

        $datetime = \DateTime::createFromFormat('Y-m-d H:i', $date . ' ' . $time);

        if (!$datetime) {
            return new JsonResponse('Fecha no valida', Response::HTTP_BAD_REQUEST);
        }

        $training = $this->session->get('training');

        if ($training === null) {
            $training = new Training($user);
            $training->setDatetime($datetime);

            $this->entityManager->persist($training);
        } else {
            $training = $this->entityManager->getRepository(Training::class)
                ->findOneBy(['id' => $training->getId()]);
            ;
            $training->setDatetime($datetime);
        }

        $this->session->set('training', $training);
        $this->entityManager->flush();

        return new RedirectResponse($this->router->generate('users_training_competitors_list'));
    }

    public function requestTraining(Request $httpRequest): Response {
        $training = $this->session->get('training');

        if ($training === null) {
            return new JsonResponse('Comienza a rellenar la formación');
        }

        $training = $this->entityManager->getRepository(Training::class)
            ->findOneBy(['id' => $training->getId()]);
        ;

        if ($training->isSent()) {
            return new JsonResponse('Ya has enviado la formación');
        }

        if ($training === null || !$training->getStudioConfirmed() || empty($training->getBlocks()) || $training->getDatetime() == null || $training->getcompetitors()->isEmpty()) {
            return new JsonResponse('Completa todos los pasos para solicitar la formación');
        }

        $training->setSent(true);
        $this->session->set('training', $training);
        $this->entityManager->flush();

        return new JsonResponse('Formación registrada correctamente a la espera de ser aprobada.');
    }

    public function cancelTraining(Request $httpRequest): Response {
        $training = $this->session->get('training');

        if ($training === null) {
            return new JsonResponse('Comienza a rellenar la formación');
        }

        $training = $this->entityManager->getRepository(Training::class)
            ->findOneBy(['id' => $training->getId()]);
        ;

        if (!$training->isSent()) {
            return new JsonResponse('Todavía no has aprobado la formación');
        }

        if ($training === null || !$training->getStudioConfirmed() || empty($training->getBlocks()) || $training->getDatetime() == null || empty($training->getcompetitors())) {
            return new JsonResponse('No has completado la formación', Response::HTTP_BAD_REQUEST);
        }

        $training->setSent(false);
        $this->session->set('training', $training);
        $this->entityManager->flush();

        return new JsonResponse('La petición se ha cancelado correctamente');
    }

    public function delete(int $idTraining): Response {
        $training = $this->entityManager->getRepository(Training::class)
            ->findOneBy(['id' => $idTraining, 'user' => $this->security->getUser()->getId()]);
        ;

        if (!$training) {
            return new JsonResponse('No se ha encontrado la formación', Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->remove($training);
        $this->entityManager->flush();

        return new RedirectResponse($this->router->generate('users_training_list'));
    }

    public function edit(int $idTraining): Response {
        $training = $this->entityManager->getRepository(Training::class)
            ->findOneBy(['id' => $idTraining, 'user' => $this->security->getUser()->getId()]);
        ;

        if (!$training) {
            return new JsonResponse('No se ha encontrado la formación', Response::HTTP_BAD_REQUEST);
        }

        $this->session->set('training', $training);

        return new RedirectResponse($this->router->generate('users_training_studio'));
    }

    public function new(): Response {
        $this->session->set('training', null);

        return new RedirectResponse($this->router->generate('users_training_studio'));
    }
}