<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');
        $indiceImage = 200;
        $indiceImage2 = 240;

        for($i = 1; $i <= 30 ; $i++) {
            $ad = new Ad();

            $title = $faker->sentence();
            //$coverImage = $faker->imageUrl(1000,350);
            
            $image = "http://placekitten.com/".$indiceImage."/200";
            $indiceImage++;
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';

            $ad->setTitle($title)
                ->setCoverImage($image)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40,200))
                ->setRooms(mt_rand(1,5));

                
            
            for($j=1; $j<= mt_rand(2,5); $j++) {
                $image = new Image();

                $image2 = "http://placekitten.com/".$indiceImage2."/200";
                $indiceImage2++;

                $image->setUrl($image2)
                        ->setCaption($faker->sentence())
                        ->setAd($ad);

                    $manager->persist($image);
            }
            $manager->persist($ad);
        }

        $manager->flush();
    }
}
