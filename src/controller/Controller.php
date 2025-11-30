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
        if ($_SERVER["REQUEST_METHOD"] === "GET"){
            $data = $this->repo->findById();
            $entity = new (ucfirst($this->entity));
            $this->arrayToObj($entity,$data);
//            echo "<pre>";
//            print_r($entity);
//            echo "</pre>";
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
        $this->repo->delete();
        echo $this->entity." with id ".$this->id." deleted";
        $this->id=null;
        $this->show();
    }
}