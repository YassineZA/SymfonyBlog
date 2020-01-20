<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        // Créer 3 catégories fakés.
        for($i=1; $i<=3; $i++) {
            $category = new Category();
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());  

            $manager->persist($category);

            // Créer à chaque catégorie entre 4 et 6 articles.
            for($j=1; $j<=mt_rand(4, 6); $j++) {
                $article = new Article();

                $content = '<p>' . join($faker->paragraphs(5), '</p><p>') . '</p>';

                $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                        ->setCategory($category);

                $manager->persist($article);     
                
                // Créer à chaque article entre 4 et 10 commentaires.
                for($k=1; $k<=mt_rand(4,10); $k++) {
                    $comment = new Comment();

                    $content = '<p>' . join($faker->paragraphs(2), '</p><p>') . '</p>';

                    // Récupérer la date d'aujourd'hui.
                    $now = new \DateTime();
                    // Récupérer la différence entre la date d'aujourd'hui et la date de création de l'article.
                    $interval = $now->diff($article->getCreatedAt());
                    // Récupérer les jours passés entre la date d'aujourd'hui et la date de création de l'article.
                    $days = $interval->days;
                    // $minimum = -100 days
                    $minimum = '-' . $days . 'days';


                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween($minimum))
                            ->setArticle($article);
                    
                    $manager->persist($comment);
                }
            }

        }        

        $manager->flush();
    }
}
