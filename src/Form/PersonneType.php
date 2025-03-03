<?php

namespace App\Form;

use App\Entity\Hobby;
use App\Entity\Job;
use App\Entity\Personne;
use App\Entity\Profile;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PersonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('firstname')
            ->add('age')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('updateAt', null, [
                'widget' => 'single_text',
            ])
            ->add('profile',EntityType::class, [
                'expanded'=> false,
                'class'=>Profile::class,
                'multiple'=>false,
                'required' =>false,
                'attr' => [
                    'class' => 'select2'
                ]
            ])
            ->add('hobbies', EntityType::class, [
                'expanded'=> false,
                'class'=>Hobby::class,
                'multiple'=>true,
                'required' =>false,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('h')
                    ->orderBy(sort:'h.designations', order:'ASC');
                },
                'attr' => [
                    'class' => 'select2'
                ]
            ])
            ->add('photo', FileType::class, [
                'label' => 'user Image (image file)',

                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using attributes
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '3024k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid iamge',
                    ])
                ],
            ])
            ->add('job', EntityType::class, [
                'expanded'=> false,
                'multiple'=>false,
                'required' =>false,
                'class'=>Job::class,
                'attr' => [
                    'class' => 'select2'
                ]
            ])
            ->add('save', SubmitType::class, ['label' => 'Create'])
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Personne::class,
        ]);
    }
}
