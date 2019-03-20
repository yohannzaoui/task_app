<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Task;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AppFixtures
 *
 * @package App\DataFixtures
 */
class AppFixtures extends Fixture
{
    /**
     * @var \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * AppFixtures constructor.
     *
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     *
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        $user = new User();
        $user->setUsername('demo');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
        $user->setEmail('demo@email.com');
        $user->validate();

        $manager->persist($user);

        $category = new Category();
        $category->setName('Perso');
        $category->setDescription('Catégorie perso');
        $category->setAuthor($user);

        $manager->persist($category);

        for ($i = 0; $i <= 20; $i++){
            $task = new Task();
            $task->setTitle($faker->text([20]));
            $task->setContent($faker->text([100]));
            $task->setImage('avatar.jpg');
            $task->setCategory($category);
            $task->setAuthor($user);

            $manager->persist($task);
        }

        $manager->flush();
    }
}
