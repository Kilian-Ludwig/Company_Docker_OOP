<?php
class Employee implements EntityInterface
{
    use EntityTrait;
    private ?int $id = null;
    private ?string $employeeFirstName = null;
    private ?string $employeeLastName = null ;
    private ?string $employeeEmail = null;
    private ?string $employeePhone = null;
    private ?int $departmentId = null;
    private ?string $position = null;
    private ?float $salary = null;
    private ?int $isActive = null;
    private DateTime | string|null $createdAt = null;
    private DateTime | string|null $updatedAt = null;

    public function setEmployeeFirstName(string $employeeFirstName): void
    {
        $this->employeeFirstName = $employeeFirstName;
    }

    public function setEmployeeLastName(string $employeeLastName): void
    {
        $this->employeeLastName = $employeeLastName;
    }

    public function setEmployeeEmail(string $employeeEmail): void
    {
        $this->employeeEmail = $employeeEmail;
    }

    public function setEmployeePhone(string $employeePhone): void
    {
        $this->employeePhone = $employeePhone;
    }

    public function setDepartmentId(int $departmentId): void
    {
        $this->departmentId = $departmentId;
    }

    public function setPosition(string $position): void
    {
        $this->position = $position;
    }

    public function setSalary(?float $salary): void
    {
        $this->salary = $salary;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }


}