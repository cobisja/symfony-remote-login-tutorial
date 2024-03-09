<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        /**
         * Admin user
         */
        UserFactory::createOne([
            'email' => UserFactory::DEFAULT_ADMIN_EMAIL,
            'roles' => ['ROLE_ADMIN']
        ]);

        /**
         * A well known user
         */
        UserFactory::createOne([
            'email' => UserFactory::DEFAULT_USER_EMAIL
        ]);

        /**
         * Random users
         */
        UserFactory::createMany(10);
    }
}
