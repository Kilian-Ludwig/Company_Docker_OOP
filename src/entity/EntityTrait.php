<?php

trait EntityTrait
{

    public function getClassName(): string
    {
        return self::class;
    }

    public function getTableName(): string
    {
        return strtolower(self::getClassName());
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;

    }
    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;

    }

//
//$isExecute =entfernt createdAt und updatedAt ; wenn id feld not set oder null ist unset ; prüft nach DateTime objekten und wandelt in datum um
//$isShow prüft nach DateTime objekten und wandelt in datum um
//$isCreate
    public function getProperties(bool $isExecute=false, bool $isShow = false,bool $isCreate = false): array
    {
        $reflection = new ReflectionClass($this);
        $props = $reflection->getProperties();
        $array = [];
        $merge = [];
        foreach ($props as $prop) {
            $v=$prop->getValue($this);
            if (!$prop->isStatic() and (!($v===null) or $isCreate)) {
                if ($isShow or $isExecute) {
                    if ($this->isDateTime($prop)) {
                        $value = $prop->getValue($this);
                        $array[$prop->getName()] = $value instanceof DateTime ? $value->format("Y-m-d") : null;
                    }
                    else {
                        $array[$prop->getName()] = $prop->getValue($this);
                    }

                }
                else {
                    $array[$prop->getName()] = $prop->getValue($this);
                }
            }
        }
        if ($isExecute) {
            unset($array["createdAt"]);
            unset($array["updatedAt"]);
            if (!isset($array["id"])){
                unset($array["id"]);
            }
        }
//        echo "<pre>";
//        print_r($array);
//        echo "</pre>";
        return $array;
    }
    private function isDateTime(ReflectionProperty $prop): bool
    {
        $type = $prop->getType();
        if (!$type instanceof ReflectionUnionType) {
            return false;
        }
        foreach ($type->getTypes() as $t) {
            if ($t instanceof ReflectionNamedType && $t->getName() === DateTime::class) {
                return true;
            }
        }

        return false;
    }

    public function getFieldType(string $key): string
    {
        $keys=[ "id"=>"number",
                "managerId" =>"number",
                "departmentId" =>"number",
                "employeeId" =>"number",
                "projectId" =>"number",
                "hoursAllocated" =>"number",
                "assignedDate" =>"date",
                "startDate" =>"date",
                "endDate" =>"date",
                "budget" =>"number",
                'employeeEmail' => 'email',
                "isActive" =>"number",
                'salary' => 'number',
                'hireDate' => 'date',
                ];
        return $keys[$key] ?? "text";

    }
//
//
//save muss noch umgeschrieben werden ? nur einzelnes objekt in csv exportieren ??
    public function save(): void
    {
        $classname = self::class;
        $csvContent = [];
        $fp = fopen($classname . '.csv', 'w');
        foreach (self::${strtolower($classname) . "_array"} as $obj) {
            $row = [];
            foreach ($obj as $attribute) {
                $row[] = $attribute;
            }
            fputcsv($fp, $row);
        }
        fclose($fp);
    }
    public function print(): string
    {
        $list = [];
        $html = "<table><thead>";
        $properties = $this->getProperties();
        $entity = strtolower(static::class);
        foreach ($properties as $key => $value) {
            if (!is_array($value)) {
                $html .= "<tr><th>" . htmlspecialchars($key) . "</th><td>" . htmlspecialchars($value) . "</td>";
                $html .= "<td><a href='index.php?entity=$entity&action=update&id={$this->id}'> <button>Ändern</button></a>";
                $html .= "<td><a href='index.php?entity=$entity&action=delete&id={$this->id}'> <button>Löschen</button></a></tr>";
            } elseif (is_array($value)) {
                foreach ($value as $set) {
                    foreach ($set as $k => $v) {
                        if (!in_array($k, array_keys($properties))) {
                            $html .= "<tr><th>" . htmlspecialchars($k) . "</th>";
                            foreach ($value as $item) {
                                $html .= "<td>" . htmlspecialchars($item[$k]) . "</td>";
                            }
                            $html .= "</tr>";
                        }
                    }
                    break;
                }

            }
        }
        $html .= "</tr></tbody></table>";
        return $html;
    }

}