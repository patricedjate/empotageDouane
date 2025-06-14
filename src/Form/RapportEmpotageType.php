<?php

namespace App\Form;

use App\Entity\RapportEmpotage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RapportEmpotageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenu',TextareaType::class,[
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"Rediger le rapport d'empotage"
                
            ])
            ->add('Submit',SubmitType::class,[
                "attr"=>[
                    "class"=>"btn btn-success mt-3 mb-3 no-print",
                    "onClick"=>"window.print()",
                ],
                "label"=>"Envoyer",
                
            ])
            ->add('images', FileType::class, [
                'label' => 'Images',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RapportEmpotage::class,
        ]);
    }
}
