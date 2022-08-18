<?php

namespace AppBundle\Twig;

use BackendBundle\Entity\User;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GetUserExtension extends \Twig_Extension{
    protected $doctrine;

    public function __construct(RegistryInterface $doctrine){
        $this->doctrine = $doctrine;
    }

    public function getFilters(){
        return array(
            new \Twig_SimpleFilter('get_user', array($this, 'getUserFilter'))
        );
    }

    //esta funcion comprueba si sigo o no al user
    //User que estoy logueado y user al que sigo (followed) - params
    public function getUserFilter($user_id){
        $user_repo  = $this->doctrine->getRepository(User::class);
        $user       = $user_repo->findOneBy([
            "id" => $user_id,
        ]);

        if(!empty($user) && is_object($user)){
            $result = $user;
        }else{
            $result = false;
        }

        return $result;
    }

    //Metodo para obtener esta extension
    public function getName(){
        return 'get_user_extension';
    }
}