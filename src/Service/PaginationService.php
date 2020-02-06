<?php

namespace App\Service;

use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
// use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginationService {
    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;
    private $route;
    private $templatePath;

    // php bin/console debug:container request

    public function __construct(EntityManagerInterface $manager, Environment $twig, RequestStack $request,
        $templatePath){
        // dump($request);
        // dump($request->getCurrentRequest()->attributes->get('_route'));
        // die();

        $this->manager      = $manager;
        $this->twig         = $twig; // s'adresser au moteur de twig
        $this->route        = $request->getCurrentRequest()->attributes->get('_route');
        $this->templatePath = $templatePath;
    }

    public function setTemplatePath($templatePath){
        $this->templatePath = $templatePath;
        return $this;
   }

   public function getTemplatePath(){
       return $this->templatePath;
   }

    public function setRoute($route){
         $this->route = $route;
         return $this;
    }

    public function getRoute(){
        return $this->route;
    }

    public function display(){
        // $this->twig->display('admin/partials/pagination.html.twig', [
        $this->twig->display($this->templatePath, [
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route
        ]);
    }

    public function getPages(){
        if (empty($this->entityClass)) {
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer !
                Utiliser la methode setEnityClass() de votre objet PaginationService !");
        }
        
        $total_enreg = count($this->manager->getRepository($this->entityClass)->findAll());

        $pages = ceil($total_enreg / $this->limit);

        return $pages;
    }

    public function getData(){
        if (empty($this->entityClass)) {
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer !
                Utiliser la methode setEnityClass() de votre objet PaginationService !");
        }
        // 1. Calculer l'offset
        $offset = $this->currentPage * $this->limit - $this->limit;
        // 2. Demander au Repository de trouver les elements
        $repo = $this->manager->getRepository($this->entityClass);
        // 3. Renvoyer les elements en question
        $data = $repo->findBy([], [], $this->limit, $offset); // findBy(tableau de critere de recherche, t. ordre, nombre d'enregistrements, à partir de 0)

        return $data;
    }

    public function setPage($page){
        $this->currentPage = $page;

        return $this;
    }

    public function getPage(){
        return $this->currentPage;
    }


    public function setLimit($limit){
        $this->limit = $limit;

        return $this;
    }

    public function getLimit(){
        return $this->limit;
    }

     public function setEntityClass($entityClass){
        $this->entityClass = $entityClass;

        return $this;
    }

    public function getEntityClass(){
        return $this->entityClass;
    }


}
















?>