<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AbstractRenderController;
use App\Entity\Training;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

class TrainingManagementController extends AbstractRenderController
{
    private EntityManagerInterface $entityManager;

    public function __construct(Environment $template, EntityManagerInterface $entityManager) {
        parent::__construct($template);
        $this->entityManager = $entityManager;
    }

    public function list(): Response
    {
        $trainingRepository = $this->entityManager->getRepository(Training::class);

        $queryBuilder = $trainingRepository->createQueryBuilder('t')
            ->select(array("t", "u"))
            ->join('t.user', 'u');

        $trainings = $queryBuilder->getQuery()->getResult();

        return $this->render('admin/users-trainings.html.twig', [
            'trainings' => $trainings
        ]);
    }
}