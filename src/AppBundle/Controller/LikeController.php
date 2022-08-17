<?php

namespace AppBundle\Controller;

use BackendBundle\Entity\User;
use BackendBundle\Entity\Like;
use BackendBundle\Entity\Publication;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LikeController extends Controller
{
    public function likeAction($id = null){
        $em                 = $this->getDoctrine()->getManager();
        $user               = $this->getUser();

        $publication_repo   = $em->getRepository(Publication::class);
        $publication        = $publication_repo->find($id);

        $like               = new Like();
        $like->setUser($user);
        $like->setPublication($publication);

        $em->persist($like);
        $flush = $em->flush();
        if($flush == null){
            $status = "You like this post";
        }else{
            $status = "Failed to save like";
        }   

        return new Response($status);
    }

    public function unlikeAction($id = null){
        $em                 = $this->getDoctrine()->getManager();
        $user               = $this->getUser();

        $like_repo          = $em->getRepository(Like::class);
        $like               = $like_repo->findOneBy([
            "user"          => $user,
            "publication"   => $id
        ]);

        $em->remove($like);
        $flush = $em->flush();
        if($flush == null){
            $status = "You don't like this post anymore";
        }else{
            $status = "Failed to save unlike";
        }   

        return new Response($status);
    }
}
