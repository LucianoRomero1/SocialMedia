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

    public function likesAction(Request $request, $nickname = null){
        $em             = $this->getDoctrine()->getManager();
        if($nickname != null){
            $user_repo  = $em->getRepository(User::class);
            $user       = $user_repo->findOneBy(["nick"=>$nickname]);
        }else{
            $user       = $this->getUser();
        }

        if(empty($user) || !is_object($user)){
            //Nos devuelve a la home si no hay user
            return $this->redirect($this->generateUrl("home_publications"));
        }

        $user_id        = $user->getId();
        $dql            = "SELECT l FROM BackendBundle:Like l WHERE l.user = $user_id ORDER BY l.id DESC";
        $query          = $em->createQuery($dql);

        $paginator      = $this->get("knp_paginator");
        $likes          = $paginator->paginate(
            $query, $request->query->getInt("page", 1),5
        );

        return $this->render("like/likes.html.twig", array(
            "user"          => $user,
            "pagination"    => $likes
        ));
    }
}
