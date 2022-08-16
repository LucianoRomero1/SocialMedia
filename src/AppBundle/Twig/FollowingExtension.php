<?php

namespace AppBundle\Twig;

use BackendBundle\Entity\Following;
use Symfony\Bridge\Doctrine\RegistryInterface;

class FollowingExtension extends \Twig_Extension{
    protected $doctrine;

    public function __construct(RegistryInterface $doctrine){
        $this->doctrine = $doctrine;
    }

    public function getFilters(){
        return array(
            new \Twig_SimpleFilter('following', array($this, 'followingFilter'))
        );
    }

    //esta funcion comprueba si sigo o no al user
    //User que estoy logueado y user al que sigo (followed) - params
    public function followingFilter($user, $followed){
        $following_repo = $this->doctrine->getRepository(Following::class);

        $user_following = $following_repo->findOneBy(array(
            "user"      => $user,
            "followed"  => $followed
        ));

        if(!empty($user_following) && is_object($user_following)){
            $result = true;
        }else{
            $result = false;
        }

        return $result;
    }

    //Metodo para obtener esta extension
    public function getName(){
        return 'following_extension';
    }
}