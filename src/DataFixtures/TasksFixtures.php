<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

/**
 * Class TasksFixtures
 *
 * @package App\DataFixtures
 */
class TasksFixtures extends Fixture
{
    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     *
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        for ($i=0; $i<=20; $i++){
            $task = new Task();
            $task->setTitle($faker->text(15));
            $task->setContent($faker->text(200));

            $manager->persist($task);
        }

        $manager->flush();
    }
}
