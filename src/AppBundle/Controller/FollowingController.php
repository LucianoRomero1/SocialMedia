<?php

namespace AppBundle\Controller;

use BackendBundle\Entity\Following;
use BackendBundle\Entity\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FollowingController extends Controller
{
    public function followAction(Request $request){
        $em             = $this->getDoctrine()->getManager();
        $user_repo      = $em->getRepository(User::class);
        
        $user           = $this->getUser();
        $followed_id    = $request->get('followed');
        //Este es el objeto user al que vamos a seguir
        $followed       = $user_repo->find($followed_id);

        $following      = new Following();
        $following->setUser($user);
        $following->setFollowed($followed);

        $em->persist($following);
        $flush = $em->flush();
        if($flush == null){
            $status = "You are following this user!!";
            // $this->addFlash('success', $status);
        }else{
            $status = "Could not follow this user";
            // $this->addFlash('error', $status);
        }

        return new Response($status);
    }

    public function unfollowAction(Request $request){
        $em             = $this->getDoctrine()->getManager();
        $user           = $this->getUser();
        $followed_id    = $request->get('followed');
        
        $following_repo = $em->getRepository(Following::class);
        $followed       = $following_repo->findOneBy(array(
            "user"      => $user,
            "followed"  => $followed_id
        ));

        $em->remove($followed);
        $flush = $em->flush();
        if($flush == null){
            $status = "You have stopped following this user";
            // $this->addFlash('success', $status);
        }else{
            $status = "Could not unfollow this user";
            // $this->addFlash('error', $status);
        }

        return new Response($status);
    }

    public function followingAction(Request $request, $nickname = null){
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
        $dql            = "SELECT f FROM BackendBundle:Following f WHERE f.user = $user_id ORDER BY f.id DESC";
        $query          = $em->createQuery($dql);

        $paginator      = $this->get("knp_paginator");
        $following      = $paginator->paginate(
            $query, $request->query->getInt("page", 1),5
        );

        return $this->render("following/following.html.twig", array(
            "type"          => "following",  
            "profile_user"  => $user,
            "pagination"    => $following
        ));
    }

    public function followedAction(Request $request, $nickname = null){
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
        $dql            = "SELECT f FROM BackendBundle:Following f WHERE f.followed = $user_id ORDER BY f.id DESC";
        $query          = $em->createQuery($dql);

        $paginator      = $this->get("knp_paginator");
        $followed       = $paginator->paginate(
            $query, $request->query->getInt("page", 1),5
        );

        return $this->render("following/following.html.twig", array(
            "type"          => "followed",  
            "profile_user"  => $user,
            "pagination"    => $followed
        ));
    }
}
