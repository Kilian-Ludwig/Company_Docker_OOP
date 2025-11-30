<?php
class Skill implements EntityInterface
{
    use EntityTrait;

    private ?int $id;
    private ?string $name = null;
    private ?string $category = null;
    private ?string $description = null;
    private DateTime | string|null $createdAt = null;
    private DateTime | string|null $updatedAt = null;

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }


}