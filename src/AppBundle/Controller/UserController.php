<?php 

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller{
    
    public function loginAction(Request $request){

        return $this->render("user/login.html.twig");

    }
    
    public function registerAction(Request $request){

        echo "Register action";
        die;

    }

}