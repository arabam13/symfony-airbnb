<?php

namespace App\Controller;

use App\Service\StatsService;
// use Doctrine\Common\Persistence\ObjectManager;
// use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(StatsService $statsService)
    {
        // $users = $manager->createQuery('SELECT count(u) FROM App\Entity\User u')->getSingleScalarResult();
        // dump($users);
        // die();
        // $ads = $manager->createQuery('SELECT count(a) FROM App\Entity\Ad a')->getSingleScalarResult();
        // $bookings = $manager->createQuery('SELECT count(b) FROM App\Entity\Booking b')->getSingleScalarResult();
        // $comments = $manager->createQuery('SELECT count(c) FROM App\Entity\Comment c')->getSingleScalarResult();

        // $users = $statsService->getUsersCount();
        // $ads = $statsService->getAdsCount();
        // $bookings = $statsService->getBookingsCount();
        // $comments = $statsService->getCommentsCount();

        $stats = $statsService->getStats();

        $bestAds = $statsService->getAdsStats('DESC');

        $worstAds = $statsService->getAdsStats('ASC');
        
        // 'stats' => compact('users', 'ads', 'bookings', 'comments'),
        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats,
            'bestAds' => $bestAds,
            'worstAds' => $worstAds
        ]);
    }
}
