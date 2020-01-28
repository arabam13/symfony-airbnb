<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    /**
     * @Route("/ads/{slug}/book", name="booking_create")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function book(Ad $ad, Request $request, ObjectManager $manager)
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $booking->setBooker($user)
                    ->setAd($ad);

            // si les dates ne sont pas disponibles, msg erreur
            if (!$booking->isBookableDate()){
                $this->addFlash(
                    'warning',
                    "Les dates que vous avez choisies, ne peuvent être réservées. Elles sont deja prises!"
                );
            }else{
                //sinon enregistrement et redirection
                $manager->persist($booking);
                $manager->flush();
    
                return $this->redirectToRoute('booking_success', [
                    'id' => $booking->getId(),
                    'withAlert' => true]); 

            }
        }

        return $this->render('booking/book.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher la page de la reservation
     * @Route("/booking/{id}", name="booking_success")
     * @param Booking $booking
     * @return Response
     */
    public function show(Booking $booking, Request $request, ObjectManager $manager){

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $comment->setAd($booking->getAd())
                    ->setAuthor($this->getUser());
            
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre commentaire a bien été pris en compte !"
            );


        }

        return $this->render('booking/show.html.twig', [
            'booking'=>$booking,
            'form'   => $form->createView()
        ]);
    }
}
