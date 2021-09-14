<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSignupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Nombre de la persona de contacto'])
            ->add('email', RepeatedType::class, [
                'type' => EmailType::class,
                'invalid_message' => 'Los emails no coinciden.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => 'Email', 'empty_data' => ' '],
                'second_options' => ['label' => 'Repite Email'],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Las contraseñas no coinciden.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => 'Contraseña', 'empty_data' => ' '],
                'second_options' => ['label' => 'Repite Contraseña'],
            ])
            ->add('phone', null, ['label' => 'Teléfono de la persona de contacto'])
            ->add('country', null, ['label' => 'Pais'])
            ->add('city', null, ['label' => 'Ciudad'])
            ->add('municipality', null, ['label' => 'Municipio'])
            ->add('address', null, ['label' => 'Dirección'])
            ->add('postalCode', null, ['label' => 'Código Postal'])
            ->add('name', null, ['label' => 'Persona de contacto'])
            ->add('companyName', null, ['label' => 'Nombre de la compañia'])
            ->add('commentary', null, ['label' => 'Comentarios'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    private function addFormElements(FormInterface $form, array $regions, array $salesHQ)
    {
        $form->add('region', EntityType::class, [
            'label' => 'Dirección Territorial',
            'class' => UserRegion::class,
            'placeholder' => 'Elige una opción',
            'choices' => $regions,
            'choice_label' => 'name',
        ]);

        $form->add('salesHQ', EntityType::class, [
            'label' => 'Jefatura Ventas',
            'class' => UserSalesHQ::class,
            'placeholder' => 'Elige una opción',
            'choices' => $salesHQ,
            'choice_label' => 'name',
        ]);
    }
}
