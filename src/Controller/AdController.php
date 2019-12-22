<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends Controller
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        // $repo = $this->getDoctrine()->getRepository(Ad::class);

        $ads = $repo->findAll();
        return $this->render('ad/index.html.twig',
             [
                'ads' => $ads
                ]);
    }

    /**
     * Fonction qui permet de creer une annonce
     * @Route("ads/new", name="ads_create")
     * @return Response
     */
    public function create(Request $request, ObjectManager $manager){
        $ad = new Ad();

        // $image = new Image();
        // $image->setUrl('http://placehold.it/1000x300')
        //        ->setCaption('Titre 1');
        // $ad->addImage($image);
           
        $form = $this->createForm(AnnonceType::class, $ad);

        $form->handleRequest($request);

        //dump($ad);

        if ($form->isSubmitted() && $form->isValid()){
            foreach($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "l'annonce <strong>". $ad->getTitle()."</strong> a bien été enregistrée !"
            );

            return $this->redirectToRoute('ads_show',[
                        'slug' => $ad->getSlug()
                    ]);
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * Fonction qui permet d'éditer une annonce
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * @return Response
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager){

        $form = $this->createForm(AnnonceType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            foreach($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications de l'annonce <strong>". $ad->getTitle()."</strong> ont bien été enregistrées !"
            );

            return $this->redirectToRoute('ads_show',[
                        'slug' => $ad->getSlug()
                    ]);
        }

        return $this->render('ad/edit.html.twig', [
            'ad'=> $ad,
            'form' => $form->createView()
        ]);

    }
            
    /**
     * Fonction qui permet d'afficher une annonce
     * @Route("/ads/{slug}", name="ads_show")
     * @return Response
     */
    // public function show($slug, AdRepository $repo)
    public function show(Ad $ad)
    {
        // Je recupere l'annonce qui correspond au slug
        // $ad = $repo->findOneBySlug($slug);

        return $this->render('ad/show.html.twig',
                [
                    'ad'=>$ad
                ]);
    }

}

        /* cela se rapporte à la fonction create()*/
        // $form = $this->createFormBuilder($ad)
        //              ->add('title')
        //              ->add('introduction')
        //              ->add('content')
        //              ->add('rooms')
        //              ->add('price')
        //              ->add('coverImage')
        //              ->add('save', SubmitType::class, [
        //                  'label'=>'Créer une nouvelle annonce',
        //                  'attr'=>[
        //                      'class'=>'btn btn-primary'
        //                  ]
        //              ])
        //              ->getForm();

?>