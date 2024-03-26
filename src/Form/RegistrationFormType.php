<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstName', TextType::class,[
            'attr'=>[
                'class'=> 'form-control',
            ],
            'required'=>false,
            'label'=> 'Prénom',
            'label_attr'=>[
                'class'=> 'form-label'
            ],
            'constraints'=>[
                new Assert\Length(['min'=>2,'max'=>50]),
                new Assert\NotBlank([
                    'message' => 'Veuillez entrer votre prénom',
                ]),
            ]
        ])
        ->add('lastName', TextType::class,[
            'attr'=>[
                'class'=> 'form-control',
            ],
            'required'=>false,
            'label'=> 'Nom',
            'label_attr'=>[
                'class'=> 'form-label'
            ],
            'constraints'=>[
                new Assert\Length(['min'=>2,'max'=>50]),
                new Assert\NotBlank([
                    'message' => 'Veuillez entrer votre nom',
                ]),
            ]
        ])
        ->add('email', EmailType::class,[
            'attr'=>[
                'class'=> 'form-control',
            ],
            'required'=>false,
            'label'=> 'E-mail',
            'label_attr'=>[
                'class'=> 'form-label'
            ],
            'constraints'=>[
                new Assert\Email([
                    'message' => 'Votre e-mail est invalide'
            ]),
                new Assert\Length(['min'=>2,'max'=>180]),
                new NotBlank([
                    'message' => 'Veuillez entrer votre e-mail',
                ]),
            ]
        ])
            ->add('address', TextType::class,[
                'attr'=>[
                    'class'=> 'form-control',
                ],
                'required'=>false,
                'label'=> 'Adresse',
                'label_attr'=>[
                    'class'=> 'form-label'
                ],
                'constraints'=>[
                    new Assert\Length(['min'=>2,'max'=>255]),
                    new NotBlank([
                        'message' => 'Veuillez entrer l\'adresse',
                    ]),
                ]
            ])
            ->add('zipcode', TextType::class,[
                'attr'=>[
                    'class'=> 'form-control',
                ],
                'required'=>false,
                'label'=> 'Code postal',
                'label_attr'=>[
                    'class'=> 'form-label'
                ],
                'constraints'=>[
                    new Assert\Length(['min'=>5,'max'=>5]),
                    new NotBlank([
                        'message' => 'Veuillez entrer le code postale',
                    ]),
                ]
            ])
            ->add('city', TextType::class,[
                'attr'=>[
                    'class'=> 'form-control',
                ],
                'required'=>false,
                'label'=> 'Ville',
                'label_attr'=>[
                    'class'=> 'form-label'
                ],
                'constraints'=>[
                    new Assert\Length(['min'=>2,'max'=>100]),
                    new NotBlank([
                        'message' => 'Veuillez entrer la ville',
                    ]),
                ]
            ])
            ->add('plainPassword', RepeatedType::class,[
                'type'=>PasswordType::class,
                'first_options'=>[
                    'attr'=>[
                        'class'=>'form-control'
                    ],
                    'label'=>'Mot de passe',
                    'label_attr'=>[
                    'class'=>'form-label'
                    ],
                ],
                'second_options'=>[
                    'attr'=>[
                        'class'=>'form-control'
                    ],
                    'label'=>"Confirm your password",
                    'label_attr'=>[
                        'class'=>'form-label'
                    ],
                ],
                'required'=>false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer le mot de passe',
                    ]),
                    new Length([
                        'max' => 4096,
                    ]),
                    new Regex ([
                        'pattern'=>'/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()]).{8,}$/',
                        'message' => 'Le mot de passe doit contenir au moins une lettre majuscule, 
                        une lettre minuscule, un chiffre et un caractère spécial, et avoir une longueur minimale de 8 caractères.'
                    ]),
                ],
                'invalid_message'=>"La confirmation du mot de passe ne correspond pas"
            ])
            ->add('agreeTerms', CheckboxType::class, [
                //ce champ n'est pas lié à une propriété spécifique d'un objet modèle
                'mapped'=>false,
                'required'=>true,
                'label'=>'J\'accepte les termes et conditions...',
                'label_attr'=>[
                    'class'=>'form-check-label'
                ],
                'attr'=>[
                    'class'=>'form-check-input'
                ],
                'constraints'=>[
                    new isTrue([
                        'message'=> 'Vous devez accepter les termes et conditions'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class,[
                'attr'=>[
                    'class'=>'btn-green'
                ],
                'label'=>"Créer le compte"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}