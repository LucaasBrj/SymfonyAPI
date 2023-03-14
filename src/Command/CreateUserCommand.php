<?php
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Console\Question\Question;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:create-user')]



class CreateUserCommand extends Command
{
    private $entityManager;
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager)
    {
        $this->hasher = $hasher;
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
        $helper = $this->getHelper('question');
        $questionEmail = new Question("Email ?");
        $questionPwd = new Question("Password ?");
        $questionPwd->setHidden(true);
        $questionPwd->setHiddenFallback(false);
        $user = new User();
        $mail = $helper->ask($input, $output, $questionEmail);
        $pwd = $helper->ask($input, $output, $questionPwd);
        $pwd = $this->hasher->hashPassword($user, $pwd);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEmail($mail);
        $user->setPassword($pwd);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $output->write('User created');
        return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->write('Error');
            return Command::FAILURE;
        }
    }
}