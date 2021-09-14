<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AbstractRenderController;
use App\Entity\Training;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class TrainingManagementController extends AbstractRenderController
{
    private EntityManagerInterface $entityManager;
    private RouterInterface $router;

    public function __construct(Environment $template, RouterInterface $router, EntityManagerInterface $entityManager) {
        parent::__construct($template);
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    public function list(): Response
    {
        $trainings = $this->entityManager->getRepository(Training::class)->findBy([], ['updatedAt' => 'DESC']);

        return $this->render('admin/users-trainings.html.twig', [
            'trainings' => $trainings
        ]);
    }

    public function delete(int $idTraining): Response
    {
        $training = $this->entityManager->getRepository(Training::class)
            ->findOneBy(['id' => $idTraining])
        ;

        if (empty($training)) {
            return new JsonResponse('No se pudo encontrar la formación', Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->remove($training);
        $this->entityManager->flush();

        return new RedirectResponse($this->router->generate('admin_trainings'));
    }

    public function enable(int $idTraining): Response
    {
        $training = $this->entityManager->getRepository(Training::class)
            ->findOneBy(['id' => $idTraining])
        ;

        if (empty($training)) {
            return new JsonResponse('No se pudo encontrar la formación', Response::HTTP_BAD_REQUEST);
        }

        $training->setEnabled(true);
        $this->entityManager->flush();

        return new RedirectResponse($this->router->generate('admin_trainings'));
    }

    public function disable(int $idTraining): Response
    {
        $training = $this->entityManager->getRepository(Training::class)
            ->findOneBy(['id' => $idTraining])
        ;

        if (empty($training)) {
            return new JsonResponse('No se pudo encontrar la formación', Response::HTTP_BAD_REQUEST);
        }

        $training->setEnabled(false);
        $this->entityManager->flush();

        return new RedirectResponse($this->router->generate('admin_trainings'));
    }
}