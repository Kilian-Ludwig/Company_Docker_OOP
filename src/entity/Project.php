<?php
class Project implements EntityInterface
{
    use EntityTrait;
    private ?int $id = null;
    private ?string $ProjectName = null;
    private ?string $description = null;
    private ?int $departmentId = null;
    private DateTime | string|null $startDate;
    private DateTime | string|null $endDate = null;
    private ?string $status = null;
    private ?float $budget = null;
    private DateTime | string|null $createdAt = null;
    private DateTime | string|null $updatedAt = null;
    public function __construct()
    {
        $this->startDate = (new DateTime())->format("Y-m-d");
    }
    public function setProjectName(string $ProjectName): void
    {
        $this->ProjectName = $ProjectName;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function setDepartmentId(int $departmentId): void
    {
        $this->departmentId = $departmentId;
    }

    public function setStartDate(DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function setEndDate(?DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function setBudget(?float $budget): void
    {
        $this->budget = $budget;
    }

}