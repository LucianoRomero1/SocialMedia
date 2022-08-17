<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PublicationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextareaType::class, array(
                "label"     => "Message",
                "required"  => true,
                "attr"      => array(
                    "class"     => "form-control" ,
                    "rows"      => "2"
                )
            ))
            ->add('image', FileType::class, array(
                "label"     => "Pic",
                "required"  => false,
                'data_class'=> null,
                "attr"      => array(
                    "class"     => "form-control" 
                )
            ))
            ->add('document', FileType::class, array(
                "label"     => "Document",
                "required"  => false,
                'data_class'=> null,
                "attr"      => array(
                    "class"     => "form-control" 
                )
            ))
            ->add('Send', SubmitType::class, array(
                "attr"      => array(
                    "class" => "btn btn-success"
                )
            ))
            ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        // Indica para que entidad es el form el dataclass
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Publication'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backendbundle_publication';
    }


}
