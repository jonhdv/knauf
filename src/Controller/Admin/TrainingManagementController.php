<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AbstractRenderController;
use App\Entity\Block;
use App\Entity\Training;
use App\Entity\User;
use App\Service\BlockManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class TrainingManagementController extends AbstractRenderController
{
    private EntityManagerInterface $entityManager;
    private RouterInterface $router;
    private BlockManager $blockManager;
    private MailerInterface $mailer;

    public function __construct(Environment $template, RouterInterface $router, EntityManagerInterface $entityManager, BlockManager $blockManager, MailerInterface $mailer) {
        parent::__construct($template);
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->blockManager = $blockManager;
        $this->mailer = $mailer;
    }

    public function list(Request $httpRequest): Response
    {
        $maxResults = 10;

        $search = $httpRequest->query->get('search', '');

        $pagination = [
            'page' => max($httpRequest->query->getInt('page', 1), 1),
            'maxResults' => $maxResults,
        ];

        $trainings = $this->entityManager->getRepository(Training::class)->findByCriteria($pagination, $search);

        return $this->render('admin/users-trainings.html.twig', [
            'trainings' => $trainings,
            'maxResults' => $maxResults,
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

        $user = $training->getUser();

        $training->setEnabled(true);
        $this->entityManager->flush();

        $mailerMail = (new TemplatedEmail())
            ->from('knaufandbreakfast@knaufandbreakfast.com')
            ->to(new Address($user->getEmail()))
            ->subject('Solicitud de formación Knauf & Breakfast aprobada')
            ->htmlTemplate('email/training-enabled-studio.html.twig')
            ->context([
                'training' => $training,
                'blocks' => $this->blockManager->getTrainingBlocks($training),
                'trainingTime' => $this->blockManager->getTrainingTime($training),
                'user' => $user
            ])
        ;

        $this->mailer->send($mailerMail);


        $emailList = [];
        $competitors = $training->getCompetitors()->toArray();

        foreach ($competitors as $competitor) {
            array_push($emailList, new Address($competitor->getEmail()));
        }

        $mailerMail = (new TemplatedEmail())
            ->from('knaufandbreakfast@knaufandbreakfast.com')
            ->to(...$emailList)
            ->subject('Has sido seleccionado para participar en la formación Knauf & Breakfast')
            ->htmlTemplate('email/training-enabled-competitor.html.twig')
            ->context([
                'training' => $training,
                'blocks' => $this->blockManager->getTrainingBlocks($training),
                'trainingTime' => $this->blockManager->getTrainingTime($training),
                'user' => $user
            ])
        ;

        $this->mailer->send($mailerMail);

        return new RedirectResponse($this->router->generate('admin_trainings'));
    }

    public function deny(int $idTraining): Response
    {
        $training = $this->entityManager->getRepository(Training::class)
            ->findOneBy(['id' => $idTraining])
        ;

        if (empty($training)) {
            return new JsonResponse('No se pudo encontrar la formación', Response::HTTP_BAD_REQUEST);
        }

        $training->setSent(false);
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