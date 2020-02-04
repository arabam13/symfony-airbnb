<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Service\PaginationService;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings/{page<\d+>?1}", name="admin_bookings_index")
     */
    public function index(BookingRepository $repo, $page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Booking::class)
                    ->setPage($page);
                    // ->setRoute('admin_bookings_index');

        // $bookings = $repo->findAll();
        return $this->render('admin/booking/index.html.twig', [
            // 'bookings' => $bookings,
            'pagination' => $pagination
        ]);
    }

    /**
     * Permet d'edit une reservation
     * @Route("/admin/bookings/{id}/edit", name="admin_booking_edit")
     * @param Booking $booking
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function edit(Booking $booking, Request $request , ObjectManager $manager){

        $form = $this->createForm(AdminBookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $booking->setAmount(0);

            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'success',
                "La reservation n° <strong>{$booking->getId()} </strong>a été modifiée !"
            );
            return $this->redirectToRoute('admin_bookings_index');

        }

        return $this->render('admin/booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une reservation
     * @Route("/admin/bookings/{id}/delete", name="admin_booking_delete")
     * @param Comment $comment
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Booking $booking, ObjectManager $manager){
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            "La réservation n°<strong>{$booking->getId()}</strong> a bien été supprimée !"
        );

    return $this->redirectToRoute('admin_bookings_index');

}
}
