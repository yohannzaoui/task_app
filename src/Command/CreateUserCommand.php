<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class CreateUserCommand
 *
 * @package App\Command
 */
class CreateUserCommand extends Command
{

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @var
     */
    private $manager;

    /**
     * @var User
     */
    private $user;

    /**
     * @var bool
     */
    private $username;

    /**
     * @var bool
     */
    private $password;

    /**
     * @var bool
     */
    private $email;


    /**
     * CreateAdminCommand constructor.
     *
     * @param \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface $encoderFactory
     * @param \Doctrine\Common\Persistence\ObjectManager                       $manager
     * @param bool                                                             $username
     * @param bool                                                             $password
     * @param bool                                                             $email
     *
     * @throws \Exception
     */
    public function __construct(
        EncoderFactoryInterface $encoderFactory,
        ObjectManager $manager,
        $username = true,
        $password = true,
        $email = true
    ) {
        parent::__construct();
        $this->encoderFactory = $encoderFactory;
        $this->manager = $manager;
        $this->user = new User();
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
    }


    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('app:create-admin')
            ->setDescription('Create admin account')
            ->setHelp("Cette commande vous assiste pour la création d'un compte utilisateur")
            ->addArgument('username', InputArgument::REQUIRED, 'Username of the user')
            ->addArgument('password', InputArgument::REQUIRED, 'password user')
            ->addArgument('email', InputArgument::REQUIRED, 'Email user');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('You are about to create an admin-user.');
        $output->writeln('Username: ' .$input->getArgument('username'));
        $output->writeln('Password: ' .$input->getArgument('password'));
        $output->writeln('Email: ' .$input->getArgument('email'));
        $output->writeln('Role: ROLE_USER');

        $passwordEncode = $this->encoderFactory->getEncoder(User::class)
            ->encodePassword($input->getArgument('password'), null);

        $this->user->setUsername($input->getArgument('username'));
        $this->user->setPassword($passwordEncode);
        $this->user->setEmail($input->getArgument('email'));
        $this->user->setRoles(['ROLE_USER']);
        $this->user->validate();

        $this->manager->persist($this->user);
        $this->manager->flush();

        $output->writeln('User successfully created');
    }

}
