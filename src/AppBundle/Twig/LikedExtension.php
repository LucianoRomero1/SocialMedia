<?php

namespace AppBundle\Twig;

use BackendBundle\Entity\Like;
use Symfony\Bridge\Doctrine\RegistryInterface;

class LikedExtension extends \Twig_Extension{
    protected $doctrine;

    public function __construct(RegistryInterface $doctrine){
        $this->doctrine = $doctrine;
    }

    public function getFilters(){
        return array(
            new \Twig_SimpleFilter('liked', array($this, 'likedFilter'))
        );
    }

    //esta funcion comprueba si sigo o no al user
    //User que estoy logueado y user al que sigo (followed) - params
    public function likedFilter($user, $publication){
        $like_repo          = $this->doctrine->getRepository(Like::class);
        $publication_liked  = $like_repo->findOneBy([
            "user"          => $user,
            "publication"   => $publication
        ]);


        if(!empty($publication_liked) && is_object($publication_liked)){
            $result = true;
        }else{
            $result = false;
        }

        return $result;
    }

    //Metodo para obtener esta extension
    public function getName(){
        return 'liked_extension';
    }
}