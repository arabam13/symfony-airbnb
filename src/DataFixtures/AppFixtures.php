<?php

namespace App\DataFixtures;


use App\Entity\Ad;
use Faker\Factory;
// use Cocur\Slugify\Slugify;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');
        // $slugify = new Slugify();

        // Gérer les Roels
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('mohamed')
                 ->setLastName('araba')
                 ->setEmail('araba@symfony.com')
                 ->setIntroduction($faker->sentence())
                 ->setDescription('<p>'. join('</p><p>', $faker->paragraphs(3)).'</p>')
                 ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                 ->setPicture('https://randomuser.me/api/portraits/men/5.jpg')
                 ->addUserRole($adminRole)
                 ;
        $manager->persist($adminUser);

        // Gerer les utilisateurs
        $users = [];
        $genres =['male', 'female'];

        for ($i=1; $i<=10; $i++){
            $user = new User();

            $genre = $faker->randomElement($genres);
            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99). '.jpg';

            if ($genre === 'male'){
                $picture  .= 'men/'.$pictureId;
            }else{
                $picture  .= 'women/'.$pictureId;
            }

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstname($genre))
                 ->setLastName($faker->lastname)
                 ->setEmail($faker->email)
                 ->setIntroduction($faker->sentence())
                 ->setDescription('<p>'. join('</p><p>', $faker->paragraphs(5)).'</p>')
                 ->setHash($hash)
                 ->setPicture($picture)
                 ;

            $manager->persist($user);
            $users[] = $user;

        }

        // Gerer les annonces
        for ($i=1; $i<=30; $i++){
            $ad = new Ad();

            $title = $faker->sentence();
            // $slug = $slugify->slugify($title);
            $coverImage = $faker->imageUrl(1000,350);
            $introduction = $faker->paragraph(2);
            $content = '<p>'. join('</p><p>', $faker->paragraphs(5)).'</p>';

            $user = $users[mt_rand(0, count($users)-1)];

            $ad->setTitle($title)
                // ->setSlug($slug)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40,100))
                ->setRooms(mt_rand(1,5))
                ->setAuthor($user);

            
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
