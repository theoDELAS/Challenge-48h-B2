<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\ArticleCategory;
use App\Entity\Role;
use App\Entity\Story;
use App\Entity\StoryCategory;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        // role admin
        $adminRole = new Role();
        $adminRole->setLabel('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setUsername('admin')
                  ->setPassword($this->encoder->encodePassword($adminUser, 'password'))
                  ->setEmail('admin@admin.com')
                  ->addRole($adminRole)
            ;
        $manager->persist($adminUser);
        // Création des différentes catégories d'annonces
        $articleCategories = array(
            'Mobilier',
            'Jeux',
            'Electroménager',
            'Immobilier'
        );

        $articleCategoriesTab = [];

        for ($i = 0; $i < sizeof($articleCategories); $i++)
        {
            $articleCategory = new ArticleCategory();
            $articleCategory->setLabel($articleCategories[$i]);

            $articleCategoriesTab[] = $articleCategory;

            $manager->persist($articleCategory);
        }

        // Création des différentes catégories d'histoires
        $storyCategories = array(
            'Histoires drôles',
            'Mêmes',
            'Annecdotes',
            'VDM',
            'Tips'
        );

        $storyCategoriesTab = [];

        for ($i = 0; $i < sizeof($storyCategories); $i++)
        {
            $storyCategory = new StoryCategory();
            $storyCategory->setLabel($storyCategories[$i]);

            $storyCategoriesTab[] = $storyCategory;

            $manager->persist($storyCategory);
        }

        // role user
        $userRole = new Role();
        $userRole->setLabel('USER');
        $manager->persist($userRole);

        // Les utilisateurs
        $users = [];

        for ($i = 1; $i <= 10; $i++)
        {
            $user = new User();

            $user->setEmail($faker->email)
                 ->addRole($userRole)
                 ->setPassword($this->encoder->encodePassword($adminUser, 'password'))
                 ->setUsername($faker->userName)
                ;

            $manager->persist($user);
            $users[] = $user;
        }

        // les histoires
        for ($i = 1; $i <= 10; $i++)
        {
            $story = new Story();

            $story->setTitle($faker->sentence($nbWords = 3, $variableNbWords = true))
                ->setContent($faker->text)
                ->setPicture('https://i.picsum.photos/'. $i .'/757/200/300.jpg')
                ->setPublicationDate($faker->dateTime($max = 'now', $timezone = null))
                ->setLastUpdateDate($faker->dateTime($max = 'now', $timezone = null))
                ->setUrl($faker->url)
                ->setUser($users[mt_rand(0, sizeof($users) - 1)])
            ;
            foreach ($storyCategoriesTab as $storyCategory) {
                if (mt_rand(0,5) > 2) {
                    $story->addStoryCategory($storyCategory);
                }
            }
            $manager->persist($story);
        }

        // les annonces
        for ($i = 1; $i <= 10; $i++)
        {
            $article = new Article();

            $article->setTitle($faker->sentence($nbWords = 3, $variableNbWords = true))
                ->setContent($faker->text)
                ->setPicture('https://i.picsum.photos/'. $i .'/757/200/300.jpg')
                ->setPublicationDate($faker->dateTime($max = 'now', $timezone = null))
                ->setLastUpdateDate($faker->dateTime($max = 'now', $timezone = null))
                ->setUrl($faker->url)
                ->setIsPublished(1)
                ->setArticleCategory($articleCategoriesTab[mt_rand(0, sizeof($articleCategories) - 1)])
                ->setUser($users[mt_rand(0, sizeof($users) - 1)])
            ;

            $manager->persist($article);
        }

        $manager->flush();
    }
}
