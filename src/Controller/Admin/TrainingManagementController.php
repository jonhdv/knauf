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
use Symfony\Component\HttpFoundation\StreamedResponse;
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

    public function export(Request $httpRequest): Response
    {
        $maxResults = 100;

        $search = $httpRequest->query->get('search', '');

        $pagination = [
            'page' => max($httpRequest->query->getInt('page', 1), 1),
            'maxResults' => $maxResults,
        ];

        $trainings = $this->entityManager->getRepository(Training::class)->findByCriteria($pagination, $search);

        $fp = fopen('php://temp', 'w');
        foreach ($trainings as $training) {
            $user = $training->getUser();

            $array = [
                $user->getEmail(),
                $user->getName(),
                $user->getAddress(),
                $user->getCountry(),
                $user->getCity()->getLabel(),
                $user->getMunicipality(),
                $user->getPostalCode(),
                $user->getCompanyName(),
                $this->blockManager->getTrainingBlocksCsv($training),
                $training->getCompetitorsList(),
                $training->getDatetime() == null ? '' : $training->getDatetime()->format('Y-m-d H:i:s'),
                $training->getStatus()['label']
            ];
            $array = array_map("utf8_decode", $array);

            fputcsv($fp, $array,';','"','\\');
        }

        rewind($fp);
        $response = new Response(stream_get_contents($fp));
        fclose($fp);

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="formaciones_'. date("d-m-Y_h:i") . '.csv"');

        return $response;
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

        $user = $training->getUser();

        $mailerMail = (new TemplatedEmail())
            ->from('knaufandbreakfast@knaufandbreakfast.com')
            ->to(new Address($user->getEmail()))
            ->subject('Solicitud de formación Knauf & Breakfast rechazada')
            ->htmlTemplate('email/training-denied-studio.html.twig')
            ->context([
                'training' => $training,
                'user' => $user
            ])
        ;

        $this->mailer->send($mailerMail);

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

        $result = $this->blockManager->getTrainingBlocks($training) . '<br>' . $this->blockManager->getTrainingTime($training);

        return new JsonResponse($result);
    }

    public function competitorsInfo(int $idTraining): Response
    {
        $training = $this->entityManager->getRepository(Training::class)
            ->findOneBy(['id' => $idTraining])
        ;

        if (empty($training)) {
            return new JsonResponse('No se pudo encontrar la formación', Response::HTTP_BAD_REQUEST);
        }

        $result = "";

        foreach ($training->getCompetitors() as $competitor) {
            $result .= $competitor->getSurname() . ',' . $competitor->getName() . '/' . $competitor->getEmail() . '/' . $competitor->getPosition()  . '/' . $competitor->getFoodIntolerances()  . '<br>';
        }

        return new JsonResponse($result);
    }

    public function userInfo(int $idUser): Response
    {
        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['id' => $idUser])
        ;

        if (empty($user)) {
            return new JsonResponse('No se pudo encontrar al usuario', Response::HTTP_BAD_REQUEST);
        }

        $result = $user->getCompanyName() . "<br>" .
            $user->getAddress() . "<br>" .
            $user->getPostalCode() . "<br>" .
            $user->getMunicipality() . "<br>" .
            $user->getCity()->getType() . "<br>" .
            $user->getCountry() . "<br>" .
            "<br>" .
            $user->getName() . "<br>" .
            $user->getPhone() . "<br>" .
            $user->getEmail() . "<br>"
        ;

        return new JsonResponse($result);
    }
}