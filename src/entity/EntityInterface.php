<?php


interface EntityInterface
{
    public function getId(): ?int;
    public function setId(int $id): void;
    public function print(): string;
    public function save(): void;
    public function getClassName(): string;
    public function getTableName(): string;

    public function getProperties(): array;

    public function getFieldType(string $key):string ;


}