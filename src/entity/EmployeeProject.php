<?php
class EmployeeProject implements EntityInterface
{
    use EntityTrait;

    private ?int $id = null;
    private ?int $employeeId = null;
    private ?int $projectId = null;
    private ?string $role = null;
    private ?int $hoursAllocated  = null;
    private DateTime | string|null $assignedDate = null;
    private DateTime | string|null $createdAt = null;
    private DateTime | string|null $updatedAt = null;


    public function setEmployeeId(int $employeeId): void
    {
        $this->employeeId = $employeeId;
    }

    public function setProjectId(int $projectId): void
    {
        $this->projectId = $projectId;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function setHoursAllocated(?int $hoursAllocated): void
    {
        $this->hoursAllocated = $hoursAllocated;
    }

    public function setAssignedDate(DateTime $assignedDate): void
    {
        $this->assignedDate = $assignedDate;
    }

}

