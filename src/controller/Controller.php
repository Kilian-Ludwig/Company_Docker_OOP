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
    public function __construct(?int $id, ?string $entity)
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
    public function show(): void
    {
        if (!isset($_SESSION["userId"])) {
            echo $this->twig->render("layout.html.twig");
            return ;
        }
        if (!$this->entity) {
            echo $this->twig->render("layout.html.twig");
        }
        elseif ($this->id) {
            $data = $this->repo->findById();
            $entity = new (ucfirst($this->entity));
            $this->arrayToObj($entity,$data);
            echo $this->twig->render("show.html.twig", ["entity" => $entity,"entityName"=>$this->entity, "className"=>$entity->getClassname()]);
        }
        else {
            $data = $this->repo->findAll();
            echo $this->twig->render("showall.html.twig",["data"=>$data,"entityName"=>$this->entity, "className"=>($data[0]->getClassname())]);
        }
    }
    public function create():void
    {
        if (!isset($_SESSION["userId"])) {
            echo $this->twig->render("layout.html.twig");
            return ;
        }
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            $entity = new (ucfirst($this->entity))();
            echo "<pre>";
            print_r($entity);
            echo "</pre>";
            echo $this->twig->render("form.html.twig", ["entity" => $entity,"className"=>$entity->getClassname(),"entityName"=>$this->entity,"action"=>"create"]);
        }
        else{
            //neues obj
            $entityObject = new (ucfirst($this->entity))();
            //mit daten aus post befüllen
            $this->arrayToObj($entityObject,$_POST);
            //objekt in db erstellen. zurück kommt array von werten des neuen obj
            $data=$this->repo->create($entityObject);
            //neues obj mit den werten füllen
            $entity = new (ucfirst($this->entity))();
            $this->arrayToObj($entity,$data);
            echo"created";
            echo $this->twig->render("show.html.twig", ["entity" => $entity,"entityName"=>$this->entity,"className"=>$entity->getClassname()]);
        }
    }
    public function update(): void
    {
        if (!isset($_SESSION["userId"])) {
            echo $this->twig->render("layout.html.twig");
            return ;
        }
        if ($_SERVER["REQUEST_METHOD"] === "GET"){
            $data = $this->repo->findById();
            echo "<pre>";
            print_r($data);
            echo "</pre>";
            $entity = new (ucfirst($this->entity));
            $this->arrayToObj($entity,$data);
            echo "<pre>";
            print_r($entity);
            echo "</pre>";
            echo $this->twig->render("form.html.twig",["entity"=> $entity,"entityName"=>$this->entity,"className"=>$entity->getClassname(), "action"=>"update"]);
        }else{
            $data = $this->repo->findById();
//            echo "<pre>";
//            print_r($data);
//            echo "</pre>";
            $entityObject = new (ucfirst($this->entity));
            $this->arrayToObj($entityObject,$data);
            $this->arrayToObj($entityObject,$_POST);

//            echo "<pre>";
//            print_r($entityObject);
//            echo "</pre>";

            $data = $this->repo->update($entityObject);
            $entity = new (ucfirst($this->entity));
            $this->arrayToObj($entity,$data);
            echo "change in the db";
            echo $this->twig->render("show.html.twig", ["entity" => $entity,"entityName"=>$this->entity,"className"=>$entity->getClassname()]);
        }
    }
    public function delete(): void
    {
        if (!isset($_SESSION["userId"])) {
            echo $this->twig->render("layout.html.twig");
            return ;
        }
        $this->repo->delete();
        echo $this->entity." with id ".$this->id." deleted";
        $this->id=null;
        $this->show();
    }

    public function logout(): void
    {
        if (!isset($_SESSION["userId"])) {
            echo $this->twig->render("layout.html.twig");
            return ;
        }
        if (isset($_POST["logout"])) {   # wenn reset button gedrückt wurde, cookies und session löschen
            session_unset()	;
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"] );
            }
            session_destroy();
            echo $this->twig->render("login.html.twig");
        }
    }

    public function login()
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
                echo $this->twig->render("layout.html.twig");
            }
        }
    }

    public function register()
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