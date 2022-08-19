<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PrivateMessageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options["empty_data"];
        $builder
            ->add('receiver', EntityType::class, array(
                "class" => "BackendBundle:User",
                "query_builder" => function($er) use($user){
                    return $er->getFollowingUsers($user);
                },
                "choice_label" => function($user){
                    return $user->getName() . " ". $user->getSurname()." - ". $user->getNick();
                },
                "label" => "To: ",
                "attr"  => array(
                    "class" => "form-select"
                )
            ))
            ->add('message', TextareaType::class, array(
                "label"     => "Message",
                "required"  => true,
                "attr"      => array(
                    "class"     => "form-control" ,
                    "rows"      => "2"
                )
            ))
            ->add('image', FileType::class, array(
                "label"     => "Image",
                "required"  => false,
                'data_class'=> null,
                "attr"      => array(
                    "class"     => "form-control" 
                )
            ))
            ->add('file', FileType::class, array(
                "label"     => "File",
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
            'data_class' => 'BackendBundle\Entity\PrivateMessage'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backendbundle_privateMessage';
    }


}
