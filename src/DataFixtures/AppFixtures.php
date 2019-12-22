<?php

namespace App\DataFixtures;


use App\Entity\Ad;
use Faker\Factory;
// use Cocur\Slugify\Slugify;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');
        // $slugify = new Slugify();


        for ($i=1; $i<=30; $i++){
            $ad = new Ad();

            $title = $faker->sentence();
            // $slug = $slugify->slugify($title);
            $coverImage = $faker->imageUrl(1000,350);
            $introduction = $faker->paragraph(2);
            $content = '<p>'. join('</p><p>', $faker->paragraphs(5)).'</p>';

            $ad->setTitle($title)
                // ->setSlug($slug)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40,100))
                ->setRooms(mt_rand(1,5));
            
            for ($j=1; $j<=mt_rand(2,3); $j++){
                $image = new Image();

                $url = $faker->imageUrl();
                
                $image->setUrl($url)
                      ->setCaption($faker->sentence())
                      ->setAd($ad);
                
                $manager->persist($image);
            
            }

            $manager->persist($ad);
        }
        
        $manager->flush();
    }
}
