<?php

namespace AppBundle\Twig;

use BackendBundle\Entity\Following;
use BackendBundle\Entity\Like;
use BackendBundle\Entity\Publication;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Response;

class UserStatsExtension extends \Twig_Extension{
    protected $doctrine;

    public function __construct(RegistryInterface $doctrine){
        $this->doctrine = $doctrine;
    }

    public function getFilters(){
        return array(
            new \Twig_SimpleFilter('user_stats', array($this, 'userStatsFilter'))
        );
    }

    public function userStatsFilter($user){
        $following_repo     = $this->doctrine->getRepository(Following::class);
        $publication_repo   = $this->doctrine->getRepository(Publication::class);
        $like_repo          = $this->doctrine->getRepository(Like::class);

        $user_following     = $following_repo->findBy(["user"=>$user]);
        $user_followers     = $following_repo->findBy(["followed"=>$user]);
        $user_posts         = $publication_repo->findBy(["user"=>$user]);
        $user_favs          = $like_repo->findBy(["user"=>$user]);

        $result             = array(
            'following' => count($user_following),
            'followers' => count($user_followers),
            'posts'     => count($user_posts),
            'favs'      => count($user_favs)
        ); 

        return $result;
    }

    //Metodo para obtener esta extension
    public function getName(){
        return 'user_stats_extension';
    }
}