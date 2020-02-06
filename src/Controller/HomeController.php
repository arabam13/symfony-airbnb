<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller {

 /**
     * Fonction qui affiche bonjour
     * @Route("/hello/{prenom}/age/{age}", name="hello_a", requirements={"age"="\d+"})
     * @Route("/bonjour", name="hello_base")
     * 
     * @return void
     */
    // public function hello($prenom="anonyme", $age=0){
    //     return new Response("Bonjour ".$prenom. ". Vous avez ".$age." ans");

    // }


    /**
     *
     * @Route("/", name="homepage")
     */
    public function home(AdRepository $adRepo, UserRepository $userRepo){
        // $prenoms=["paul"=>18,"jean"=>29,"rémi"=>59];

        return $this->render("home.html.twig", [
                'ads' => $adRepo->findBestAds(3),
                'users'=> $userRepo->findBestUsers()
            ]
        );
    }
    
}

// return $this->render(
//     "home.html.twig",
//     [
//         'title'=>'Bonjour à tous', 
//         'age'=>14,
//         'tableau'=>$prenoms
//     ]
// );
    

?>