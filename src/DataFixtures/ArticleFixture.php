<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ArticleFixture extends Fixture
{
    private $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create("fr_FR");

        // on veut associer a notre premier utilisateur tiut les articles quon va creer 
        //$user1 = $manager->getRepository(User::class)->find(7);

        $user1 = new User();
        $mdp = $this->hasher->hashPassword($user1 , "123456");

        $user1->setEmail("user@yahoo.fr")
              ->setRoles(["ROLE_ADMIN"])
              ->setPassword($mdp);

        for($i = 0 ; $i < 100; $i++){

            $article = new Article();
            $article->setTitre($faker->words(6 , true))
                    ->setContenu($faker->paragraph(5))
                    ->setUser($user1);
            $manager->persist($article);
        }

        $manager->flush();
        //symfony console doctrine:fixture:load --append
        //symfony console doctrine:fixture:load 

        
    }
}
