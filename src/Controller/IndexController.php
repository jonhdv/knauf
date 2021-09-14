<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserSignupType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractRenderController
{
    private EntityManagerInterface $entityManager;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        Environment $template,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        UserPasswordEncoderInterface $passwordEncoder,
        SessionInterface $session,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($template);
        $this->router = $router;
        $this->formFactory = $formFactory;
        $this->passwordEncoder = $passwordEncoder;
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function index(Request $httpRequest): Response
    {
        $form = $this->formFactory->create(UserSignupType::class)
            ->add('reset', SubmitType::class, ['label' => 'Registrarse', 'attr' => ['class' => 'btn btn-primary btn-block']]);

        $form->handleRequest($httpRequest);

        return $this->render('index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function signin (Request $httpRequest): Response
    {
       $email = $httpRequest->request->get('email');
       $name = $httpRequest->request->get('name');
       $address = $httpRequest->request->get('address');
       $phone = $httpRequest->request->get('phone');
       $country = $httpRequest->request->get('country');
       $city = $httpRequest->request->get('city');
       $municipality = $httpRequest->request->get('municipality');
       $postalCode = $httpRequest->request->get('postalCode');
       $companyName = $httpRequest->request->get('companyName');
       $commentary = $httpRequest->request->get('commentary');

       if ($email == null || $name == null || $address == null || $phone == null || $country == null || $city == null || $municipality == null || $postalCode == null || $companyName == null) {
           return new JsonResponse('Datos incorrectos', Response::HTTP_BAD_REQUEST);
       }

        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => $email]);
        ;
        if (!empty($user)) {
            return new JsonResponse('Ya hya un uusario registrado con este email', Response::HTTP_BAD_REQUEST);
        }

        $user = new User();

        $user
            ->setInitialRole()
            ->setEnabled(false);
        ;

        $user->setEmail($email);
        $user->setName($email);
        $user->setAddress($email);
        $user->setPhone($email);
        $user->setCountry($email);
        $user->setCity($email);
        $user->setMunicipality($email);
        $user->setPostalCode($email);
        $user->setCompanyName($email);
        $user->setCommentary($email);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(true);
    }
}