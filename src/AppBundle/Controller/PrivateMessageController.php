<?php

namespace AppBundle\Controller;

use AppBundle\Form\PrivateMessageType;
use BackendBundle\Entity\User;
use BackendBundle\Entity\PrivateMessage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class PrivateMessageController extends Controller
{
    private $session;

    public function __construct(){
        $this->session = new Session();
    }

    public function indexAction(Request $request){
        $em             = $this->getDoctrine()->getManager();
        $user           = $this->getUser();
        $message        = new PrivateMessage();
        $form           = $this->createForm(PrivateMessageType::class, $message, array(
            "empty_data" => $user
        ));

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //upload image
            $file = $form["image"]->getData();
            if(!empty($file) && $file != null){
                $ext = $file->guessExtension();
                if($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "gif"){
                    $file_name  = $user->getId().time().'.'.$ext;
                    $file->move("uploads/messages/images", $file_name);

                    $message->setImage($file_name);
                }else{
                    $status     = "Invalid format";
                }
            }else{
                $message->setImage(null);
            }
            //upload doc
            $doc = $form["file"]->getData();
            if(!empty($doc) && $doc != null){
                $ext = $doc->guessExtension();
                if($ext == "pdf"){
                    $doc_name  = $user->getId().time().'.'.$ext;
                    $doc->move("uploads/messages/files", $doc_name);

                    $message->setFile($doc_name);
                }else{
                    $status     = "Invalid format";
                }
            }else{
                $message->setFile(null);
            }

            $message->setEmitter($user);
            $message->setCreatedAt(new \DateTime("now"));
            $message->setReaded(0);

            $em->persist($message);
            $flush = $em->flush();
            if($flush == null){
                $status = "Message sent successfully";
            }else{
                $status = "Error sending message";
            }

            $this->session->getFlashBag()->add("status", $status);
            return $this->redirectToRoute("message_index");

        }else{
            $status = "Message can not be send";
        }

        $private_messages = $this->getPrivateMessages($request);
        $this->setReaded($em, $user);

        return $this->render("messages/index.html.twig", array(
            "user"          => $user,
            "form"          => $form->createView(),
            "pagination"    => $private_messages
        ));
    }

    public function sendedAction(Request $request){
        $private_messages = $this->getPrivateMessages($request, "sended");

        return $this->render("messages/sended.html.twig", array(
            "pagination" => $private_messages
        ));
    }

    public function getPrivateMessages($request, $type = null){
        $em             = $this->getDoctrine()->getManager();
        $user           = $this->getUser();
        $user_id        = $user->getId();

        if($type == "sended"){
            $dql = "SELECT p FROM BackendBundle:PrivateMessage p WHERE p.emitter = $user_id ORDER BY p.id DESC";
        }else{
            $dql = "SELECT p FROM BackendBundle:PrivateMessage p WHERE p.receiver = $user_id ORDER BY p.id DESC";
        }

        $query          = $em->createQuery($dql);
        $paginator  = $this->get("knp_paginator");
        $pagination = $paginator->paginate(
            $query, $request->query->getInt("page", 1), 5
        );

        return $pagination;
    }

    public function notReadedAction(){
        $em             = $this->getDoctrine()->getManager();
        $user           = $this->getUser();
        $message_repo   = $em->getRepository(PrivateMessage::class);
        $msg_not_readed = count($message_repo->findBy(["receiver"=>$user, "readed"=> 0]));

        return new Response($msg_not_readed);
    }

    public function setReaded($em, $user){
        $message_repo   = $em->getRepository(PrivateMessage::class);
        $messages       = $message_repo->findBy(["receiver"=>$user, "readed"=> 0]);

        foreach ($messages as $msg) {
            $msg->setReaded(1);
            $em->persist($msg);
        }

        $flush = $em->flush();
        if($flush == null){
            $result = true;
        }else{
            $result = false;
        }

        return $result;
    }
}
