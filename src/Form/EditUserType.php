<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstName', TextType::class,[
            'attr'=>[
                'class'=> 'form-control',
                'minlength'=>2,
                'maxlength'=>50
            ],
            'label'=> 'Prénom',
            'label_attr'=>[
                'class'=> 'form-label'
            ],
            'constraints'=>[
                new Assert\Length(['min'=>2,'max'=>50]),
                new Assert\NotBlank()
            ]
        ])
        ->add('lastName', TextType::class,[
            'attr'=>[
                'class'=> 'form-control',
                'minlength'=>2,
                'maxlength'=>50
            ],
            'label'=> 'Nom',
            'label_attr'=>[
                'class'=> 'form-label'
            ],
            'constraints'=>[
                new Assert\Length(['min'=>2,'max'=>50]),
                new Assert\NotBlank()
            ]
        ])
        ->add('email', EmailType::class,[
            'attr'=>[
                'class'=> 'form-control',
                'minlength'=>2,
                'maxlength'=>180
            ],
            'label'=> 'Adresse mail',
            'label_attr'=>[
                'class'=> 'form-label'
            ],
            'constraints'=>[
                new Assert\Email(),
                new Assert\Length(['min'=>2,'max'=>180]),
                new Assert\NotBlank()
            ]
        ])
            ->add('address', TextType::class,[
                'attr'=>[
                    'class'=> 'form-control',
                    'minlength'=>2,
                    'maxlength'=>255
                ],
                'label'=> 'Adresse',
                'label_attr'=>[
                    'class'=> 'form-label'
                ],
                'constraints'=>[
                    new Assert\Length(['min'=>2,'max'=>255]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('zipcode', TextType::class,[
                'attr'=>[
                    'class'=> 'form-control',
                    'minlength'=>2,
                    'maxlength'=>255
                ],
                'label'=> 'Code Postal',
                'label_attr'=>[
                    'class'=> 'form-label'
                ],
                'constraints'=>[
                    new Assert\Length(['min'=>5,'max'=>5]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('city', TextType::class,[
                'attr'=>[
                    'class'=> 'form-control',
                    'minlength'=>2,
                    'maxlength'=>100
                ],
                'label'=> 'Ville',
                'label_attr'=>[
                    'class'=> 'form-label'
                ],
                'constraints'=>[
                    new Assert\Length(['min'=>2,'max'=>100]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('favAddress', TextType::class,[
                'attr'=>[
                    'class'=> 'form-control',
                    'minlength'=>2,
                    'maxlength'=>255
                ],
                'required'=>false,
                'label'=> 'Adresse',
                'label_attr'=>[
                    'class'=> 'form-label'
                ],
                'constraints'=>[
                    new Assert\Length(['min'=>2,'max'=>255]),
                ]
            ])
            ->add('favZipcode', TextType::class,[
                'attr'=>[
                    'class'=> 'form-control',
                    'minlength'=>2,
                    'maxlength'=>255
                ],
                'required'=>false,
                'label'=> 'Code Postal',
                'label_attr'=>[
                    'class'=> 'form-label'
                ],
                'constraints'=>[
                    new Assert\Length(['min'=>5,'max'=>5]),
                ]
            ])
            ->add('favCity', TextType::class,[
                'attr'=>[
                    'class'=> 'form-control',
                    'minlength'=>2,
                    'maxlength'=>100
                ],
                'required'=>false,
                'label'=> 'Ville',
                'label_attr'=>[
                    'class'=> 'form-label'
                ],
                'constraints'=>[
                    new Assert\Length(['min'=>2,'max'=>100]),
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'Veuillez entrer votre mot de passe pour enregistrer les modifications',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
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
            ])
            ->add('submit', SubmitType::class,[
                'attr'=>[
                    'class'=>'btn-green'
                ],
                'label'=>"Sauvegarder"
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