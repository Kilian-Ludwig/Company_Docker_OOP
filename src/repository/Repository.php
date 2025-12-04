<?php

//
//sollte man vielleicht eine propertie in repository machen, die PDO speichert ?
class Repository
{
    private ?string $entity;
    private ?int  $id;
    private PDO $con;

    /**
     * @param PDO $con
     */
    public function __construct(?string $entity, ?int $id)
    {
        $this->con = $this->db_connect();
        $this->entity = $entity;
        $this->id = $id;
    }

    public function db_connect() : PDO
    {
        $dbHost = $_ENV['DB_HOST'];
        $dbName = $_ENV['DB_NAME'];
        $dbUser = $_ENV['DB_USER'];
        $dbPw = $_ENV['DB_USER_PW'];
        return new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPw, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    public function findAll() : array | false
    {
        if (in_array($this->entity, ["department","employee","employeeProject","project","skill"])) {
            $sql = "select * from `{$this->entity}`";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            $con = new Controller();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function findById() : array | false{
        if (in_array($this->entity, ["department","employee","employeeProject","project","skill"])) {
            $sql = "select * from `{$this->entity}` where id=:id";
            $stmt = $this->con->prepare($sql);
            $stmt->execute(["id"=>$this->id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        else {
            return false;
        }
    }
    public function findByEmail(string $email): false|array
    {
        if (in_array($this->entity, ["employee","users"])) {
            $sql = "SELECT * FROM `{$this->entity}` WHERE `email` = :email LIMIT 1";
            $stmt = $this->con->prepare($sql);
            $stmt->execute(['email' => $email]);
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getPassword(string $email): false|string
    {
        if (in_array($this->entity, ["users"])) {
            $sql = "SELECT passwordHash FROM `{$this->entity}` WHERE `email` = :email";
            $stmt = $this->con->prepare($sql);
            $stmt->execute(['email' => $email]);
        }
        $hash =$stmt->fetch(PDO::FETCH_ASSOC);
        if ($hash) {
            return $hash["passwordHash"];
        }
        return false;
    }

    public function update(EntityInterface $entity) : EntityInterface |false |array {
        $columnString = "";
        $column = [];
        if (in_array($entity->getTableName(), ["department","employee","employeeProject","project","skill"])) {
            $sql = "Describe `{$entity->getTableName()}`";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            $table = $stmt->fetchAll(PDO::FETCH_COLUMN);
            foreach ($table as $key => $value) {
                if (!in_array($value,["id","createdAt","updatedAt"])) {
                    $column []= "`$value` = :$value";
                    }
                }
            $columnString = implode(",", $column);
            #UPDATE tabellenname SET spalte1 = wert1, spalte2 = wert2, ...WHERE bedingung;
            $sql = "UPDATE `{$entity->getTableName()}` SET $columnString WHERE id=:id";
            echo "<pre>";
            print_r($sql);
            echo "</pre>";
            echo "<pre>";
            print_r($entity->getProperties(true));
            echo "</pre>";
            $stmt = $this->con->prepare($sql);
            $stmt->execute($entity->getProperties(true));
            return $this->findById();
        }
        return false;
        }



    public function create(EntityInterface $entity) : EntityInterface | false |array
    {
        $columnString = "";
        $columnStringArray = [];
        $columnStringValue = "";
        $columnStringValueArray = [];
        if (in_array($entity->getTableName(), ["users","department", "employee","employeeProject","project","skill"])) {
            $sql = "Describe `{$entity->getTableName()}`";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            $table = $stmt->fetchAll(PDO::FETCH_COLUMN);
            foreach ($table as $key => $value) {
                if (!in_array($value, ["id", "createdAt", "updatedAt"])) {
                    $columnStringArray []= "`$value`";
                    $columnStringValueArray []= ":$value";
                }
            }
            $columnString = implode(",", $columnStringArray);
            $columnStringValue = implode(",", $columnStringValueArray);
            $sql = "INSERT INTO `{$entity->getTableName()}` ($columnString) VALUES ($columnStringValue)";
            #INSERT INTO table_name (column1, column2, column3, ...) VALUES (value1, value2, value3, ...);
            $stmt = $this->con->prepare($sql);
            $stmt->execute($entity->getProperties(true));
            $this->id =$this->con->lastInsertId();
            return $this->findById();
        }
        return false;
    }

    public function delete() : int | false {
        if (in_array($this->entity, ["department","employee","employeeProject","project","skill"])) {
            $sql = "Delete from `{$this->entity}` where id=:id";
            $stmt = $this->con->prepare($sql);
            $stmt->execute(["id"=> $this->id]);
            return (intval($this->id));
        }
        return false;
    }
}