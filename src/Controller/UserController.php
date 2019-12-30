<?php

namespace App\Controller;

use App\Entity\User;
 use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class UserController extends Controller
{
    /**
     * @Route("/user/{slug}", name="user_show")
     */
    public function index($slug, UserRepository $repo)
    {
        $user = $repo->findOneBySlug($slug);
        return $this->render('user/index.html.twig', [
            'user' => $user
        ]);
    }
}
