<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Task;
use App\Form\TaskType;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

/**
 * @codeCoverageIgnore
 */
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $task = new Task();
            $task->setCreatedAt($faker->dateTimeBetween('-2 years', 'now'));
            $task->setTitle('Tache n°' . $i);
            $task->setContent($faker->sentence($nbWords = 10, $variableNbWords = true));
            $task->toggle(rand(0, 1));
            
            $manager->persist($task);
        }

        $manager->flush();
    }
}
