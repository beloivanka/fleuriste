<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class QuantityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('quantity', ChoiceType::class, [
            'choices' => array_combine(range(1, 5), range(1, 5)),
            'attr'=>[
                'class'=> 'form-select'
            ],
            'label'=>'Quantité',
                'label_attr'=>[
                    'class'=>'form-label bold-text'
                ]
        ])
        ->add('submit', SubmitType::class,[
            'attr'=>[
                'class'=>'btn-green mt-4 mb-4'
            ],
            'label'=>"Ajouter dans le panier"
        ])
    ;
    }

}