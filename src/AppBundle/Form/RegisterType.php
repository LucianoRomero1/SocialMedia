<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegisterType extends AbstractType
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
            ->add('password', PasswordType::class, array(
                "label"     => "Password",
                "required"  => true,
                "attr"      => array(
                    "class"     => "form-password form-control" 
                )
            ))
            ->add('Register', SubmitType::class, array(
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
