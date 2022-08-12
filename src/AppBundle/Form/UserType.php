<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                "label"     => "Name",
                "required"  => true,
                "attr"      => array(
                    "class"     => "form-name form-control" 
                )
            ))
            ->add('surname', TextType::class, array(
                "label"     => "Surname",
                "required"  => true,
                "attr"      => array(
                    "class"     => "form-surname form-control" 
                )
            ))
            ->add('nick', TextType::class, array(
                "label"     => "Nick",
                "required"  => true,
                "attr"      => array(
                    "class"     => "form-nick form-control nick-input" 
                )
            ))
            ->add('email', EmailType::class, array(
                "label"     => "Email",
                "required"  => true,
                "attr"      => array(
                    "class"     => "form-email form-control" 
                )
            ))
            ->add('bio', TextareaType::class, array(
                "label"     => "Biography",
                "required"  => false,
                "attr"      => array(
                    "class"     => "form-bio form-control" ,
                    "rows"      => "5"
                )
            ))
            ->add('image', FileType::class, array(
                "label"     => "Profile photo",
                "required"  => false,
                'data_class'=> null,
                "attr"      => array(
                    "class"     => "form-photo form-control" 
                )
            ))
            ->add('Save', SubmitType::class, array(
                "attr"      => array(
                    "class"     => "form-submit btn btn-success"
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
            'data_class' => 'BackendBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backendbundle_user';
    }


}
