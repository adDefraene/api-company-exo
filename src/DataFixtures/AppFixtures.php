<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * Initialize the password hasher
     *
     * @var object
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        //INIT FAKER
        $faker = Factory::create("fr-FR");

        //CREATE THE ADMIN USER
        $adminUser = new User();
        $adminUser->setEmail("adriendefraene@gmail.com")
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordEncoder->encodePassword($adminUser, "Password!"))
            ->setFirstName("Adrien")
            ->setLastName("Defraene")
        ;
        $manager->persist($adminUser);

        //CREATE CATEGORIES
        for($c=0 ; $c < 10 ; $c++)
        {
            $category = new Category();
            $category->setTitle($faker->word());
            $manager->persist($category);

            //CREATE PRODUCT OF THIS CATEGORY
            for($p=0 ; $p < rand(2,8) ; $p++){
                $product = new Product();
                $product->setTitle($faker->sentence(rand(2,5), true))
                    ->setPrice(rand(100,9999)/100)
                    ->setDescription($faker->paragraph(4, true))
                    ->setCategory($category)
                ;
                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
