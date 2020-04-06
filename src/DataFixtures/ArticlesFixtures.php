<?php

// namespace App\DataFixtures;

// use Doctrine\Bundle\FixturesBundle\Fixture;
// use Doctrine\Common\Persistence\ObjectManager;
// use App\Entity\Article;
// use App\Entity\Category;

// class ArticlesFixtures extends Fixture
// {
//     public function load(ObjectManager $manager)
//     {

//         $faker = \Faker\Factory::create('fr_FR');

//         // Création de catégories
//         for($i = 0; $i < 2; $i++) {
//             $category = new Category();
//             $category->setTitle($faker->sentence())
//                      ->setDescription($faker->paragraph());
                    
//             $manager->persist($category);

//             // Créer 4 à 6 articles
//             for ($j = 1; $j <= mt_rand(4, 6); $j++) {
//                 $article = new Article();

//                 $content = '<p>' . join($faker->paragraphs(5), '</p><p>') . '</p>';

//                 $article->setTitle($faker->sentence())
//                         ->setContent($content)
//                         ->setImage($faker->imageUrl())
//                         ->setPrice($faker->randomNumber(2))
//                         ->setCategory($category);
    
//                 $manager->persist($article);
//             }
//         }


//         $manager->flush();
//     }
// }
