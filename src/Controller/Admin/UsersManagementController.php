<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AbstractRenderController;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Twig\Environment;

class UsersManagementController extends AbstractRenderController
{
    private EntityManagerInterface $entityManager;
    private RouterInterface $router;
    private MailerInterface $mailer;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(Environment $template, RouterInterface $router, EntityManagerInterface $entityManager, MailerInterface $mailer, UserPasswordEncoderInterface $passwordEncoder) {
        parent::__construct($template);
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->mailer = $mailer;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function list(Request $httpRequest): Response
    {
        $maxResults = 10;

        $search = $httpRequest->query->get('search', '');

        $pagination = [
            'page' => max($httpRequest->query->getInt('page', 1), 1),
            'maxResults' => $maxResults,
        ];

        $users = $this->entityManager->getRepository(User::class)->findByCriteria($pagination, $search);

        return $this->render('admin/users-management.html.twig', [
            'users' => $users,
            'maxResults' => $maxResults
        ]);
    }

    public function enable(int $idUser): Response {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $idUser]);

        if (empty($user)) {
            return new JsonResponse('No se pudo encontrar la formación', Response::HTTP_BAD_REQUEST);
        }

        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = 'knauf_';
        for ($i = 0; $i < 5; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        $user->setPassword($this->passwordEncoder->encodePassword($user, $randomString));

        $mailerMail = (new TemplatedEmail())
            ->from('knaufandbreakfast@knaufandbreakfast.com')
            ->to(new Address($user->getEmail()))
            ->subject('Solicitud Knauf aprobada')
            ->htmlTemplate('email/signup-accepted.html.twig')
            ->context([
                'name' => $user->getName(),
                'mail' => $user->getEmail(),
                'pass' => $randomString
            ])
        ;

        $this->mailer->send($mailerMail);

        $mailerMail = (new TemplatedEmail())
            ->from('knaufandbreakfast@knaufandbreakfast.com')
            ->to(new Address($user->getEmail()))
            ->subject('Información de uso de Knauf')
            ->htmlTemplate('email/signup-accepted-info.html.twig')
            ->context([
                'name' => $user->getName()
            ])
        ;

        $this->mailer->send($mailerMail);

        $user->setEnabled(true);
        $this->entityManager->flush();

        return new RedirectResponse($this->router->generate('admin_users_management'));
    }

    public function disable(int $idUser): Response {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $idUser]);

        if (empty($user)) {
            return new JsonResponse('No se pudo encontrar la formación', Response::HTTP_BAD_REQUEST);
        }

        $user->setEnabled(false);
        $this->entityManager->flush();

        return new RedirectResponse($this->router->generate('admin_users_management'));
    }
}