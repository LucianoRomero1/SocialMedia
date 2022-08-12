<?php 

namespace AppBundle\Controller;

use BackendBundle\Entity\User;
use AppBundle\Form\RegisterType;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller{
    
    public function loginAction(Request $request){

        if(is_object($this->getUser())){
            return $this->redirect("home");
        }

        //Servicio de authenticacion de Symfony
        $authenticationUtils= $this->get('security.authentication_utils');
        $error              = $authenticationUtils->getLastAuthenticationError();
        //Te saca el ultimo usuario que intentó loguearse para setearlo en el value del email por si pifiaste la PW
        $last_username      = $authenticationUtils->getLastUsername();

        return $this->render("user/login.html.twig", array(
            "last_username" => $last_username,
            "error"         => $error
        ));

    }
    
    public function registerAction(Request $request){

        if(is_object($this->getUser())){
            return $this->redirect("home");
        }

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest(($request));
        if($form->isSubmitted() && $form->isValid()){
            //Obtengo los datos que llegan por POST en el form
            $email      = $form->get("email")->getData();
            $nick       = $form->get("nick")->getData();
            $password   = $form->get("password")->getData();
            
            $em         = $this->getDoctrine()->getManager();
            //$user_repo   = $em->getRepository(User::class);

            $query      = $em->createQueryBuilder();
            $query      ->select("u")
                        ->from("BackendBundle:User", "u")
                        ->where("u.email = :email")
                        ->orWhere("u.nick = :nick")
                        ->setParameters(array(
                            "email" => $email,
                            "nick" => $nick
                        ));

            $user_isset= $query->getQuery()->getResult();
            if(count($user_isset) == 0){
                //Servicio de symfony para la password
                $factory    = $this->get("security.encoder_factory");
                $encoder    = $factory->getEncoder($user);

                $password   = $encoder->encodePassword($password, $user->getSalt());

                $user->setPassword($password);
                $user->setRole("ROLE_USER");
                $user->setImage(null);

                $em->persist($user);
                $flush = $em->flush();

                //Valida el flush
                if($flush == null){
                    $status = "Registered successfully";

                    $this->addFlash('success',$status);
                    return $this->redirect("login");
                }else{
                    $status = "You have not registered correctly";
                }
            }else{
                $status = "User already exist";
            }
            $this->addFlash('error',$status);
        }

        return $this->render("user/register.html.twig", array(
            "form" => $form->createView()
        ));

    }

    public function nickTestAction(Request $request){
        $nick       = $request-> get("nick");

        $em         = $this->getDoctrine()->getManager();
        $user_repo  = $em->getRepository(User::class);
        $user_isset = $user_repo->findBy(["nick" => $nick]);

        $result     = "used";
        if(count($user_isset) >= 1 || is_object($user_isset)){
            $result = "used";
        }else{
            $result = "unused";
        }

        return new Response($result);
    }

    public function editUserAction(Request $request){

        $user       = $this->getUser();
        $user_image = $user->getImage();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest(($request));
        if($form->isSubmitted() && $form->isValid()){
            //Obtengo los datos que llegan por POST en el form
            $email      = $form->get("email")->getData();
            $nick       = $form->get("nick")->getData();
            
            $em         = $this->getDoctrine()->getManager();
            //$user_repo   = $em->getRepository(User::class);

            $query      = $em->createQueryBuilder();
            $query      ->select("u")
                        ->from("BackendBundle:User", "u")
                        ->where("u.email = :email")
                        ->orWhere("u.nick = :nick")
                        ->setParameters(array(
                            "email" => $email,
                            "nick" => $nick
                        ));

            $user_isset= $query->getQuery()->getResult();
            if($user->getEmail() == $user_isset[0]->getEmail() && $user->getNick() == $user_isset[0]->getNick() || count($user_isset) == 0){
                //Upload file
                $file = $form->get("image")->getData();
                if(!empty($file) && $file != null){
                    //Validate extension
                    $ext = $file->guessExtension(); 
                    if($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "gif"){
                        //Le armo un nombre para guardarlo en uploads
                        $file_name  = $user->getId().time().'.'.$ext;
                        //Guardarlo en el fichero uploads
                        $file->move("uploads/users", $file_name);

                        $user->setImage($file_name);
                    }else{
                        $status     = "Invalid format to profile photo";
                    }
                }else{
                    //En el caso de no setear ninguna, guarda la que ya tenia
                    $user->setImage($user_image);
                }
                
                $em->persist($user);
                $flush = $em->flush();

                //Valida el flush
                if($flush == null){
                    $status = "User updated successfully";

                    $this->addFlash('success',$status);
                    return $this->redirect("my-data");
                }else{
                    $status = "The information could not be modified";
                }
            }else{
                $status = "User already exist";
            }
            
            $this->addFlash('error',$status);
        }
        
        return $this->render("user/edit_user.html.twig", array(
            "form"  => $form->createView()
        ));
    }
}