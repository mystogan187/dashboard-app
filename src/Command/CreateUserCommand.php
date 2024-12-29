<?php

namespace App\Command;

use App\Dashboard\User\Domain\Entity\User;
use App\Dashboard\User\Domain\ValueObjects\UserEmail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Creates a new admin user',
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $email = UserEmail::from('admin@admin.com');

            $tempUser = User::create(
                'Administrator',
                $email,
                ['ROLE_ADMIN'],
                'temp'
            );

            $hashedPassword = $this->passwordHasher->hashPassword($tempUser, 'Alex123');

            $user = User::create(
                'Administrator',
                $email,
                ['ROLE_ADMIN'],
                $hashedPassword
            );

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $io->success(sprintf('Admin user created successfully with email: %s', $email->value()));

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }
    }
}