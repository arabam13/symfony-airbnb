<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    // @Route("/admin/ads/{page}", name="admin_ads_index", requirements={"page":"\d+"})
    
    /**
     *  @Route("/admin/ads/{page<\d>?1}", name="admin_ads_index")
     */
    public function index(AdRepository $repo, $page, PaginationService $pagination)
    {
        // $limit = 10;

        // $start = $page * $limit - $limit;
               // 1 * 10 = 10 - 10 = 0
               // 2 * 10 = 20 -10 = 10

        // $total_enreg = count($repo->findAll());
        // $pages = ceil($total_enreg / $limit);
        
        $pagination->setEntityClass(Ad::class)
                    ->setPage($page);
                    // ->setRoute('admin_ads_index');
        return $this->render('admin/ad/index.html.twig', [
            // // 'ads' => $repo->findAll(),
            // 'ads' => $repo->findBy([], [], $limit, $start),  // (tableau de critere de recherche, t. ordre, nombre d'enregistrements, à partir de 0)
            // 'pages' => $pages,
            // 'page' => $page
            'pagination'=>$pagination
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'edition
     * @Route("admin/ads/{id}/edit", name="admin_ads_edit")
     * @param Ad $ad
     * @return Response
     */
    public function edit (Ad $ad, Request $request, ObjectManager $manager){
        $form = $this->createForm(AnnonceType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "Lannonce <strong>{$ad->getTitle()} </strong>a été enregistrée!"
            );

        }

        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une annonce
     * @Route("admin/ads/{id}/delete", name="admin_ads_delete")
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Ad $ad, ObjectManager $manager){
        if (count($ad->getBookings()) > 0){
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer cette annonce <strong>{$ad->getTitle()}</strong>,
                car pour celle-ci, il y'a des réservations en cours "
            );
        }else{
            $manager->remove($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !"
            );
        }

        return $this->redirectToRoute('admin_ads_index');

    }
}
