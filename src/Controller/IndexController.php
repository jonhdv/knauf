<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\CityType;
use App\Entity\User;
use App\Form\UserSignupType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractRenderController
{
    private EntityManagerInterface $entityManager;
    private UserPasswordEncoderInterface $passwordEncoder;
    private MailerInterface $mailer;

    public function __construct(
        Environment $template,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        UserPasswordEncoderInterface $passwordEncoder,
        SessionInterface $session,
        MailerInterface $mailer,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($template);
        $this->router = $router;
        $this->formFactory = $formFactory;
        $this->passwordEncoder = $passwordEncoder;
        $this->session = $session;
        $this->mailer = $mailer;
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
       $city = CityType::create($httpRequest->request->get('city', ''));
       $municipality = $httpRequest->request->get('municipality');
       $postalCode = $httpRequest->request->get('postalCode');
       $companyName = $httpRequest->request->get('companyName');
       $commentary = $httpRequest->request->get('commentary');

       if ($email == "" || $name == "" || $address == "" || $phone == "" || $country == "" || $city == "" || $municipality == "" || $postalCode == "" || $companyName == "") {
           return new JsonResponse('Datos incorrectos');
       }

        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => $email]);
        ;
        if (!empty($user)) {
            return new JsonResponse('Ya se ha realizado una petición de registro con este mail');
        }

        $user = new User();

        $user
            ->setInitialRole()
            ->setEnabled(false);
        ;

        $user->setEmail($email);
        $user->setName($name);
        $user->setAddress($address);
        $user->setPhone($phone);
        $user->setCountry($country);
        $user->setCity($city);
        $user->setMunicipality($municipality);
        $user->setPostalCode($postalCode);
        $user->setCompanyName($companyName);
        $user->setCommentary($commentary);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $mailerMail = (new TemplatedEmail())
            ->from('knaufandbreakfast@knaufandbreakfast.com')
            ->to(new Address($email))
            ->subject('Solicitud de información Knauf')
            ->htmlTemplate('email/signup-request.html.twig')
            ->context([
                'name' => $name,
            ])
        ;
        $this->mailer->send($mailerMail);

        return new JsonResponse(true);
    }
}