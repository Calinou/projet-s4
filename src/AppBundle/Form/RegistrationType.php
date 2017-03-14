<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('prenom', TextType::class,    ['label' => 'Prénom', 'required' => false]);
        $builder->add('nom', TextType::class,       ['label' => 'Nom', 'required' => false]);
        $builder->add('infos', TextareaType::class, ['label' => 'Biographie (200 caractères max.)', 'required' => false]);
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
}
