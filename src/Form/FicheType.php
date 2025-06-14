<?php

namespace App\Form;

use App\Entity\Fiche;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichImageType;

class FicheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_cda',TextType::class,[
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"Nom CDA",
                "label_attr"=>[
                    "class"=>"form-class"
                ]
            ])
            ->add('nom_exportateur',TextType::class,[
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"Nom Exporteur",
                "label_attr"=>[
                    "class"=>"form-class"
                ]
            ])
            ->add('nature_marchandise',TextType::class,[
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"Nature Marchandise",
                "label_attr"=>[
                    "class"=>"form-class"
                ]
            ])
            ->add('colis_marchandise',TextType::class,[
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"Colis Marchandise",
                "label_attr"=>[
                    "class"=>"form-class"
                ]
            ])
            ->add('poidsNet_marchandise',TextType::class,[
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"poids Net Marchandise",
                "label_attr"=>[
                    "class"=>"form-class"
                ]
            ])
            ->add('conditionnement_marchandise',TextType::class,[
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"Conditionnement Marchandise",
                "label_attr"=>[
                    "class"=>"form-class mt-2"
                ]
            ])
            ->add('lieuEmpotage_marchandise',TextType::class,[
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"Lieu Empotage Marchandise",
                "label_attr"=>[
                    "class"=>"form-class mt-2"
                ]
            ])
            ->add('nconteneurs',TextType::class,[
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"N° Conteneurs",
                "label_attr"=>[
                    "class"=>"form-class"
                ]
            ])
            ->add('typeTC',TextType::class,[
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"Type TC",
                "label_attr"=>[
                    "class"=>"form-class"
                ]
            ])
            ->add('nplombs',TextType::class,[
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"N° Plombs",
                "label_attr"=>[
                    "class"=>"form-class"
                ]
            ])
            /*
            ->add('pdfFiles', FileType::class, [
                'label' => 'Ajouter des fichiers PDF',
                'mapped' => false, // on gère nous-mêmes l'upload
                'required' => false,
                'multiple' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => ['application/pdf'],
                        'mimeTypesMessage' => 'Veuillez uploader uniquement des fichiers PDF.',
                    ])
                ],
            ])
                */
            ->add('fichiers', FileType::class, [
            'label' => 'Fichiers PDF',
            'multiple' => true,
            'mapped' => false,
            'required' => false,
        ])
        ->add('latitude', HiddenType::class)
        ->add('longitude', HiddenType::class)
            ->add('Submit',SubmitType::class,[
                "attr"=>[
                    "class"=>"btn btn-success mt-3 mb-3",
                    
                ],
                "label"=>"Envoyer"
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fiche::class,
        ]);
    }
}
