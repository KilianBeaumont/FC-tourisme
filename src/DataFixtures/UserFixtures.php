<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{

    private UserPasswordHasherInterface $userPasswordHasher;

    /**
     * @param UserPasswordHasherInterface $userPasswordHasher
     */
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker= Factory::create('fr_FR');
        for($i=1;$i<50;$i++){
            $user = new User();
            $user->setNom($faker->lastName)
                 ->setPrenom($faker->firstName)

                 ->setPseudo($faker->userName)

                 ->setEstActif($faker->boolean(50))
                 ->setCreatedAt(New \DateTime())
                 ->setEmail($faker->email);

                $nombre=$faker->numberBetween(1,3);

                    if ($nombre==1) {
                        $role = ["ROLE_USER"];
                    }elseif ($nombre == 2){
                        $role = ["ROLE_RESTAURANT"];
                    }else{
                        $role = ["ROLE_ADMIN"];
                    }

                 $user->setRoles($role);
                    $passwordHashed = $this->userPasswordHasher->hashPassword($user,'secret');
                 $user->setPassword($passwordHashed);


            $manager->persist($user);
        }

        $manager->flush();
    }
}
