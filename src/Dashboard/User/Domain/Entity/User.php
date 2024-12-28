<?php

namespace App\Dashboard\User\Domain\Entity;

use App\Dashboard\User\Domain\ValueObjects\UserEmail;
use App\Dashboard\User\Domain\ValueObjects\UserId;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private string $email;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private string $password;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $profilePhoto = null;

    private function __construct(
        string $name,
        UserEmail $email,
        array $roles,
        string $hashedPassword
    ) {
        $this->name = $name;
        $this->email = $email->value();
        $this->roles = $roles;
        $this->password = $hashedPassword;
    }

    public static function create(
        string $name,
        UserEmail $email,
        array $roles,
        string $hashedPassword
    ): self {
        return new self($name, $email, $roles, $hashedPassword);
    }

    public function id(): ?UserId
    {
        return $this->id ? UserId::from($this->id) : null;
    }

    public function email(): UserEmail
    {
        return UserEmail::from($this->email);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function profilePhoto(): ?string
    {
        return $this->profilePhoto;
    }

    public function update(
        string $name,
        UserEmail $email,
        array $roles
    ): void {
        $this->name = $name;
        $this->email = $email->value();
        $this->roles = $roles;
    }

    public function updatePassword(string $hashedPassword): void
    {
        $this->password = $hashedPassword;
    }

    public function updateProfilePhoto(?string $fileName): void
    {
        $this->profilePhoto = $fileName;
    }

    // Implementación de UserInterface
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
    }
}