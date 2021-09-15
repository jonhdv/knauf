<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AbstractRenderController;
use App\Entity\Block;
use App\Entity\Training;
use App\Service\BlockManager;
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
    private BlockManager $blockManager;

    public function __construct(Environment $template, RouterInterface $router, EntityManagerInterface $entityManager, BlockManager $blockManager) {
        parent::__construct($template);
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->blockManager = $blockManager;
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

    public function blocksInfo(int $idTraining): Response
    {
        $training = $this->entityManager->getRepository(Training::class)
            ->findOneBy(['id' => $idTraining])
        ;

        if (empty($training)) {
            return new JsonResponse('No se pudo encontrar la formación', Response::HTTP_BAD_REQUEST);
        }

        $result = $this->blockManager->getTrainingBlocks($training) . '<br><br>' . $this->blockManager->getTrainingTime($training);

        return new JsonResponse($result);
    }
}