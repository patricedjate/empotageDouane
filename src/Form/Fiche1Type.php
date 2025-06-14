<?php

namespace App\Form;

use App\Entity\Fiche;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Fiche1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_cda')
            ->add('nom_exportateur')
            ->add('nature_marchandise')
            ->add('colis_marchandise')
            ->add('poidsNet_marchandise')
            ->add('conditionnement_marchandise')
            ->add('lieuEmpotage_marchandise')
            ->add('nconteneurs')
            ->add('typeTC')
            ->add('nplombs')
            ->add('date')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fiche::class,
        ]);
    }
}
