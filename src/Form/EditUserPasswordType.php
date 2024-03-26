<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class EditUserPasswordType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('plainPassword', PasswordType::class, [
            'mapped' => false,
            'attr'=>[
                'class'=>'form-control'
            ],
            'label'=>'Mot de passe actuel',
            'label_attr'=>[
                'class'=>'form-label mt-4'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer un mot de passe',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],
        ])
        ->add('newPassword', RepeatedType::class,[
            'type'=>PasswordType::class,
            'first_options'=>[
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'Nouveau mot de passe',
                'label_attr'=>[
                'class'=>'form-label'
                ],
            ],
            'second_options'=>[
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>"Confirmez votre nouveau mot de passe",
                'label_attr'=>[
                    'class'=>'form-label'
                ],
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer le mot de passe',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],
            'invalid_message'=>"La confirmation du mot de passe ne correspond pas"
        ])
        ->add('submit', SubmitType::class,[
            'attr'=>[
                'class'=>'btn-green'
            ],
            'label'=>"Sauvegarder"
        ]);
        }

}