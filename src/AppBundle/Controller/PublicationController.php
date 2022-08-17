<?php 

namespace AppBundle\Controller;

use AppBundle\Form\PublicationType;
use BackendBundle\Entity\Following;
use BackendBundle\Entity\Publication;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class PublicationController extends Controller{

    private $session;

    public function __construct(){
        $this->session = new Session();
    }

    public function indexAction(Request $request){

        $em             = $this->getDoctrine()->getManager();
        $publication    = new Publication();
        $form           = $this->createForm(PublicationType::class, $publication);
        $user           = $this->getUser();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            //upload image
            $file = $form["image"]->getData();
            if(!empty($file) && $file != null){
                $ext = $file->guessExtension();
                if($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "gif"){
                    $file_name  = $user->getId().time().'.'.$ext;
                    $file->move("uploads/publications", $file_name);

                    $publication->setImage($file_name);
                }else{
                    $status     = "Invalid format";
                }
            }else{
                $publication->setImage(null);
            }
            //upload doc
            $doc = $form["document"]->getData();
            if(!empty($doc) && $doc != null){
                $ext = $doc->guessExtension();
                if($ext == "pdf"){
                    $doc_name  = $user->getId().time().'.'.$ext;
                    $doc->move("uploads/documents", $doc_name);

                    $publication->setDocument($doc_name);
                }else{
                    $status     = "Invalid format";
                }
            }else{
                $publication->setDocument(null);
            }

            $publication->setUser($user);
            $publication->setCreatedAt(new \DateTime("now"));
            $em->persist($publication);
            $flush = $em->flush();
            if($flush == null){
                $status = "Post created";
            }else{
                $status = "Post can not be created";
            }

            //addflash pero con session
            $this->session->getFlashBag()->add("status", $status);
            return $this->redirectToRoute("home_publications");
        }else{
            $status = "Post not created, invalid form";
        }

        $publications = $this->getPublications($request);

        return $this->render('publication/home.html.twig', [
           "form"       => $form->createView(),
           "pagination" => $publications
        ]);

    }

    public function getPublications(Request $request){
        $em                 = $this->getDoctrine()->getManager();
        $user               = $this->getUser();
        $publication_repo   = $em->getRepository(Publication::class);
        $following_repo     = $em->getRepository(Following::class);

        $following          = $following_repo->findBy(["user"=>$user]);
        $followingArray     = array();

        foreach ($following as $follow) {   
            //Array solo de ID de a quienes sigo
            $followingArray[] = $follow->getFollowed();
        }

        $query = $publication_repo->createQueryBuilder("p")
                ->where("p.user = (:user_id) OR p.user IN (:following)")
                ->setParameter("user_id", $user->getId())
                ->setParameter("following", $followingArray)
                ->orderBy("p.id", "DESC")
                ->getQuery();

        $paginator  = $this->get("knp_paginator");
        $pagination = $paginator->paginate(
            $query, $request->query->getInt("page", 1), 5
        );

        return $pagination;

    }

    public function deletePublicationAction(Request $request, $id = null){
        $em                 = $this->getDoctrine()->getManager();
        $publication_repo   = $em->getRepository(Publication::class);
        $publication        = $publication_repo->find($id);
        $user               = $this->getUser();

        if($user->getId() == $publication->getUser()->getId()){
            $em->remove($publication);
            $flush = $em->flush();
            if($flush == null){
                $status = 'Post deleted';
            }else{
                $status = 'Could not delete this post';
            }
        }else{
            $status = 'Could not delete this post';
        }

        return new Response($status);
        
    }

}