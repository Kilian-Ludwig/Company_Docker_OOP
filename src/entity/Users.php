<?php
class Users implements EntityInterface
{
    use EntityTrait;
    private ?int $id = null;

    private string $email;

    private string $passwordHash;

    private string $role = 'user';

    private int $isActive = 1;

    private ?DateTime $createdAt = null;

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function setIsActive(int $isActive): void
    {
        $this->isActive = $isActive;
    }

}