<?php

namespace App\DataFixtures;

use App\Factory\DummyFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        DummyFactory::createMany(10);
    }
}
