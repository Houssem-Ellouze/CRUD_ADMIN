<?php

namespace App\Form;

use App\Entity\Admin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Âge',
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
            ])
            ->add('login', TextType::class, [
                'label' => 'Login',
            ])
            ->add('mdp', TextType::class, [
                'label' => 'Mot de passe',
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Admin::class,
        ]);
    }
}
