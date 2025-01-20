<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Film;
use App\Entity\User;
use App\Entity\Category;


use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Data extends Fixture 
{
    /* kernelInterface $appKernel */
    private $appKernel;
    private $rootDir;
    protected $encoder;

    public function __construct(kernelInterface $appKernel,  UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->appKernel = $appKernel;
        $this->rootDir = $appKernel->getProjectDir();
    }



    public function load(ObjectManager $manager): void
    {
        // use the factory to create a Faker\Generator instance
        $faker = Factory::create('fr_FR');

        // Films
        $filename = $this->rootDir . '/src/DataFixtures/Data/films.json';
        $data = file_get_contents($filename);

        $films_json = json_decode($data);

        // tableau de films
        $films = [];
        foreach ($films_json as $film_item) {
            $film = new Film();
            $film->setName($film_item->name)
                ->setSlug($film_item->slug)
                ->setDescription($film_item->description)
                ->setImageUrls($film_item->imageUrls)
                ->setSoldePrice($film_item->solde_price * 100)
                ->setRegularPrice($film_item->regular_price * 100);

            $films[] = $film;

            // Gestion de commentaires 
            // if (mt_rand(0, 1)) {
            //     $comment = new Comment();
            //     $comment->setContent($faker->paragraph())
            //         ->setRating(mt_rand(1, 5))
            //         ->setAuthor($user)
            //         ->setFilm($film);

            //     $manager->persist($comment);
            // }

            $manager->persist($film);
        }


        // Users

        $filename = $this->rootDir . '/src/DataFixtures/Data/users.json';
        $data = file_get_contents($filename);

        $users_json = json_decode($data);
        $users = [];
        foreach ($users_json as $user_item) {
            $user = new User();
            $hash = $this->encoder->hashPassword($user, "password");
            $user->setFullName($user_item->fullName)
                ->setCivility($user_item->civility)
                ->setEmail($user_item->email)
                ->setPassword($hash);

            $users[] = $user;

            $manager->persist($user);

            $manager->flush();
        }


        // Categories 
        $categories = ["Action", "Fantastique", "Series", "Commedie", "Dramatique", "Thriler", "Drame", "Sf"];
        foreach ($categories as $name) {
            # code...
            $category = new Category();
            $category->setName($name)
                ->setDescription($film_item->description);

            $manager->persist($category);
        }

        $manager->flush();
    }
}