<?php 

namespace AppBundle\Controller;

use BackendBundle\Entity\User;
use AppBundle\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller{
    
    public function loginAction(Request $request){

        return $this->render("user/login.html.twig");

    }
    
    public function registerAction(Request $request){

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
        }else{
            $status = "Invalid fields";
        }
        
        $this->addFlash('error',$status);

        return $this->render("user/register.html.twig", array(
            "form" => $form->createView()
        ));

    }

}