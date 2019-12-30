<?php

namespace App\Form;

use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, $this->getConfiguration("Ancien MDP:","Votre Ancien MDP"))
            ->add('newPassword', PasswordType::class, $this->getConfiguration("Nouveau MDP:","Votre Nouveau MDP"))
            ->add('confirmPassword', PasswordType::class, $this->getConfiguration("Confirmation Nouveau MDP:","Confirmer votre Nouveau MDP"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
