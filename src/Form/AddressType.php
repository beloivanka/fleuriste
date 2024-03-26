<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstName', TextType::class,[
            'attr'=>[
                'class'=> 'form-control',
                'minlength'=>2,
                'maxlength'=>255,
            ],
            'label'=> 'Prénom',
            'label_attr'=>[
                'class'=> 'form-label'
            ],
            'constraints'=>[
                new Assert\Length(['min'=>2,'max'=>255]),
                new Assert\NotBlank()
            ]
        ])
        ->add('lastName', TextType::class,[
            'attr'=>[
                'class'=> 'form-control',
                'minlength'=>2,
                'maxlength'=>255,
            ],
            'label'=> 'Nom',
            'label_attr'=>[
                'class'=> 'form-label'
            ],
            'constraints'=>[
                new Assert\Length(['min'=>2,'max'=>255]),
                new Assert\NotBlank()
            ]
        ])
        ->add('note', TextareaType::class,[
            'attr'=>[
                'class'=> 'form-control',
                'minlength'=>2,
                'maxlength'=>550,
            ],
            'label'=> 'Votre mot pour la carte (facultatif)',
            'label_attr'=>[
                'class'=> 'form-label'
            ],
            'required' => false,
            'constraints'=>[
                new Assert\Length(['max'=>255]),
            ]
        ])
            ->add('address', TextType::class,[
                'attr'=>[
                    'class'=> 'form-control',
                    'minlength'=>2,
                    'maxlength'=>255,
                ],
                'label'=> 'Address',
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
                'label'=> 'ZIP Code',
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
                'label'=> 'City',
                'label_attr'=>[
                    'class'=> 'form-label'
                ],
                'constraints'=>[
                    new Assert\Length(['min'=>2,'max'=>100]),
                    new Assert\NotBlank()
                ]
            ])
            ->add ('checkbox', CheckboxType::class,[
                'attr'=>[
                    'class'=> 'form-check-input mt-4'
                ],
                'required' => false,
                'label'=> 'Sauvegarder pour les futures commandes',
                'label_attr'=>[
                    'class'=> 'form-check-label mt-4'
                ]
            ])
            ->add('submit', SubmitType::class,[
                'attr'=>[
                    'class'=>'btn-green'
                ],
                'label'=>"Commander"
            ])
        ;
    }

    // public function configureOptions(OptionsResolver $resolver): void
    // {
    //     $resolver->setDefaults([
    //         'data_class' => Order::class,
    //     ]);
    // }
}
