<?php

namespace App\Dashboard\Profile\Domain\Entity;

use App\Dashboard\Profile\Domain\ValueObjects\Email;
use App\Dashboard\Profile\Domain\ValueObjects\Password;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'profiles')]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email;

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $profilePhoto = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    private function __construct(
        string $name,
        Email $email,
        Password $hashedPassword,
    ) {
        $this->name = $name;
        $this->email = $email->value();
        $this->password = $hashedPassword->value();
        $this->roles[] = 'ROLE_USER';
    }

    public static function create(
        string $name,
        Email $email,
        Password $hashedPassword,
    ): self {
        return new self($name, $email, $hashedPassword);
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function profilePhoto(): ?string
    {
        return $this->profilePhoto;
    }

    public function roles(): array
    {
        return $this->roles;
    }

    public function update(string $name, Email $email): void
    {
        $this->name = $name;
        $this->email = $email->value();
    }

    public function updatePassword(Password $hashedPassword): void
    {
        $this->password = $hashedPassword->value();
    }

    public function updateProfilePhoto(?string $fileName): void
    {
        $this->profilePhoto = $fileName;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles, true);
    }

    public function addRole(string $role): void
    {
        if (!$this->hasRole($role)) {
            $this->roles[] = $role;
        }
    }

    public function removeRole(string $role): void
    {
        if (($key = array_search($role, $this->roles, true)) !== false) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }
    }

    public function passwordMatches(string $plainPassword, callable $passwordVerifier): bool
    {
        return $passwordVerifier($this->password, $plainPassword);
    }
}