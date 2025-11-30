<?php
class Department implements EntityInterface{
    use EntityTrait;
    private ?int $id = null;
    private ?string $departmentName = null;
    private ?string $description = null;
    private ?int $managerId = null;
    private ?int $isActive = null;
    private ?int $isHiring = null;
    private DateTime | string | null $createdAt = null;
    private DateTime | string | null $updatedAt = null;

    public function setDepartmentName(string $departmentName): void
    {
        $this->departmentName = $departmentName;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function setManagerId(?int $managerId): void
    {
        $this->managerId = $managerId;
    }

    public function setIsActive(int $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function setIsHiring(int $isHiring): void
    {
        $this->isHiring = $isHiring;
    }

}
