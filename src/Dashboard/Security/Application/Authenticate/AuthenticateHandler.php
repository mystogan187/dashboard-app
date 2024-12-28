<?php

namespace App\Dashboard\Security\Application\Authenticate;

use App\Dashboard\User\Domain\Infrastructure\UserRepository;
use App\Dashboard\User\Domain\ValueObjects\UserEmail;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AuthenticateHandler
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly JWTTokenManagerInterface $jwtManager
    ) {}

    public function __invoke(AuthenticateCommand $command): AuthenticationResponse
    {
        $user = $this->repository->findByEmail(UserEmail::from($command->email()));

        if ($user === null) {
            throw new AuthenticationException('Invalid credentials');
        }

        if (!$this->passwordHasher->isPasswordValid($user, $command->password())) {
            throw new AuthenticationException('Invalid credentials');
        }

        return new AuthenticationResponse(
            $this->jwtManager->create($user),
            $user->email()->value(),
            $user->name(),
            $user->getRoles()
        );
    }
}