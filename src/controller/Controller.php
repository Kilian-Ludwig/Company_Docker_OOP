<?php


class Controller implements ControllerInterface
{
    private ?int $id ;
    private ?string $entity;
    private Repository $repo ;
    private \Twig\Environment $twig ;

    /**
     * @param int $id
     * @param string $entity
     */
    public function __construct(?int $id=null , ?string $entity = null)
    {
        $this->id = $id;
        $this->entity = $entity;
        $this->repo = new Repository($this->entity, $this->id);
        $loader = new \Twig\Loader\FilesystemLoader('../templates/entity');
        $this->twig = new \Twig\Environment($loader, []);
    }
    public function arrayToObj(EntityInterface &$entity, array $array) : EntityInterface
    {
        foreach ($array as $key => $value) {
            //build setter method
            $setter = "set" . ucfirst($key);
            //post values are all strings -> check and convert to "real" datatype
            if (method_exists($entity, $setter)) {
                if ($value === '' || $value === null) {
                    $value = null;
                } elseif (is_numeric($value) && str_contains($value, '.')) {
                    $value = (float)$value;  // "1.5" - 1.5
                } elseif (is_numeric($value)) {
                    $value = (int)$value;    // "1" - 1
                } elseif ($this->isDate($key)) {
                    $value = isset($value) ? new DateTime($value) : null;
                }
                    $entity->$setter($value);
            }
        }
        return $entity;

    }
    private function isDate(string $fieldName): bool
    {
        $dateTimeFields = [
            "createdAt",
            "updatedAt",
            "startDate",
            "endDate",
            "hireDate",
            "hoursAllocated",
            "assignedDate",
        ];
        return in_array($fieldName, $dateTimeFields, true);
    }
    public function show(): void # $erros als argument fehlt noch
    {
        if (!isset($_SESSION["userId"])) {
            $errors = ["you need login first!"];
            header("Location: http://www.company.bbq/users/login");
            exit;
        }

        elseif ($this->id) {
            try {
                $data = $this->repo->findById();
                $entity = new (ucfirst($this->entity));
                $this->arrayToObj($entity,$data);
                echo $this->twig->render("show.html.twig", ["entity" => $entity,"entityName"=>$this->entity, "className"=>$entity->getClassname()]);
            } catch (Exception $e) {
                echo "Fehler: " . $e->getMessage();
                $errors = ["could not find entity ".$this->entity];
                echo $this->twig->render("form.html.twig", ["errors"=>$errors,"entity" => $entity,"className"=>$entity->getClassname(),"entityName"=>$this->entity,"action"=>"create"]);
            }
        }
        else {
            try {
                $datas = $this->repo->findAll();
                foreach ($datas as $d) {
                    $obj = (new (strtoupper($this->entity)));
                    $data [] = $this->arrayToObj($obj,$d);
                }
                echo $this->twig->render("showall.html.twig",["data"=>$data,"entityName"=>$this->entity, "className"=>($data[0]->getClassname())]);
            }
            catch (Exception $e) {
                echo "Fehler: " . $e->getMessage();
                $errors = ["could not find ".$this->entity." list"];
                echo $this->twig->render("welcome.html.twig", ["errors"=>$errors]);
            }
        }
    }
    public function create():void
    {
        if (!isset($_SESSION["userId"])) {
            $errors = ["you need login first!"];
            echo $this->twig->render("login.html.twig", ["errors" => $errors]);
            exit ;
        }
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            $entity = new (ucfirst($this->entity))();
            echo $this->twig->render("form.html.twig", ["entity" => $entity,"className"=>$entity->getClassname(),"entityName"=>$this->entity,"action"=>"create"]);
        }
        else{
            try {
                //neues obj
                $entityObject = new (ucfirst($this->entity))();
                //mit daten aus post befüllen
                $this->arrayToObj($entityObject, $_POST);
                //objekt in db erstellen. zurück kommt array von werten des neuen obj
                $data = $this->repo->create($entityObject);
                //neues obj mit den werten füllen
                $entity = new (ucfirst($this->entity))();
                $this->arrayToObj($entity, $data);
                echo "created";
                echo $this->twig->render("show.html.twig", ["entity" => $entity, "entityName" => $this->entity, "className" => $entity->getClassname()]);
            }
            catch (Exception $e) {
                $errors = ['Das Objekt konnte nicht gespeichert werden. Prüfe die ID. Departments denen Mitarbeiter angehören dürfen nicht gelöscht werden.'];
                try {
                    echo $this->twig->render("form.html.twig", ["errors"=>$errors,"entity" => $entity,"className"=>$entity->getClassname(),"entityName"=>$this->entity,"action"=>"create"]);
                } catch (Exception $e) {
                    echo "Fehler: " . $e->getMessage();
                    $entity = new (ucfirst($this->entity))();
                    echo $this->twig->render("form.html.twig", ["errors"=>$errors,"entity" => $entity,"className"=>$entity->getClassname(),"entityName"=>$this->entity,"action"=>"create"]);
                }
            }
        }
    }
    public function update(): void
    {
        if (!isset($_SESSION["userId"])) {
            $errors = ["you need login first!"];
            echo $this->twig->render("login.html.twig", ["errors" => $errors]);
            exit ;
        }
        if ($_SERVER["REQUEST_METHOD"] === "GET"){
            try {
                $data = $this->repo->findById();
                $entity = new (ucfirst($this->entity));
                $this->arrayToObj($entity,$data);
                echo $this->twig->render("form.html.twig",["entity"=> $entity,"entityName"=>$this->entity,"className"=>$entity->getClassname(), "action"=>"update"]);
            } catch (Exception $e) {
                echo "Fehler: " . $e->getMessage();
                $errors = ["Could not load ".$this->entity];
                $this->show($errors);

            }
        }else{
            try {
                $data = $this->repo->findById();
                $entityObject = new (ucfirst($this->entity));
                $this->arrayToObj($entityObject,$data);
                $this->arrayToObj($entityObject,$_POST);
                $data = $this->repo->update($entityObject);
                $entity = new (ucfirst($this->entity));
                $this->arrayToObj($entity,$data);
                echo "change in the db";
                echo $this->twig->render("show.html.twig", ["entity" => $entity,"entityName"=>$this->entity,"className"=>$entity->getClassname()]);
            }
            catch (Exception $e) {
                echo "Fehler: " . $e->getMessage();
                $errors = ["Could not update ".$this->entity];
                $this->show($errors);
            }
        }
    }
    public function delete(): void
    {
        if (!isset($_SESSION["userId"])) {
            $errors = ["you need login first!"];
            echo $this->twig->render("login.html.twig", ["errors" => $errors]);
            exit ;
        }
        try {
            $this->repo->delete();
            echo $this->entity." with id ".$this->id." deleted";
            $this->id=null;
            $this->show();
        } catch (Exception $e) {
            echo "Fehler: " . $e->getMessage();
            $errors = ["Could not delete ".$this->entity];
            $this->show($errors);
        }
    }

    public function logout(): void
    {
        if (!isset($_SESSION["userId"])) {
            $errors = ["you need login first!"];
            echo $this->twig->render("login.html.twig", ["errors" => $errors]);
            exit ;
        }
        session_unset()	;
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"] );
        }
        session_destroy();
        header("Location: http://www.company.bbq");
        exit;
    }


    public function login() : void
    {
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            echo $this->twig->render("login.html.twig", ["status" => true]);
        }
        else {
            $passwordHashDb = $this->repo->getPassword($_POST["email"]);
            if (!$passwordHashDb) {
                echo $this->twig->render("login.html.twig", ["status" => false]);
            }
            elseif (password_verify($_POST["password"],$passwordHashDb)) {
                $userId = $this->repo->findByEmail($_POST["email"])["id"];
                $_SESSION['userId'] = $userId;
                echo $this->twig->render("welcome.html.twig");
            }
        }
    }

    public function showMainpage():void
    {
        if (!isset($_SESSION["userId"])) {
            $errors = ["you need login first!"];
            $this->login();
            exit;
        }
        else {
            echo $this->twig->render("welcome.html.twig", ["status" => true]);
            exit;

        }

    }

    public function register():void
    {
        if ($_SERVER["REQUEST_METHOD"] === "GET"){
            echo $this->twig->render("register.html.twig");
        }
        else {
            $entity = new (ucfirst($this->entity));
            $register = new Registration();
            if (!$this->repo->findByEmail($_POST["email"]) and $register->validatePassword($_POST["password"])) {
                $entity->setPasswordHash(password_hash($_POST["password"], PASSWORD_DEFAULT));
                $entity->setEmail($_POST["email"]);
                echo "<pre>";
                print_r($_POST);
                echo "</pre>";
                echo "<pre>";
                print_r($entity);
                echo "</pre>";
                $this->repo->create($entity);
                echo "<pre>";
                print_r($entity);
                echo "</pre>";
                echo $this->twig->render("login.html.twig", ["status" => true]);
                echo"registered";
            }
            else {
                print_r($register->getErrors());
                echo $this->twig->render("register.html.twig");

            }


        }
        
    }
}