<?php

class Kernel
{

    private array $request;
    private ControllerInterface $controller;
    private ?string $entity;
    private ?string $method;
    private ?int $id;

    private function loadRequest() : void
    {
        $this->request = explode("/", strtolower($_SERVER["REQUEST_URI"]));
    }
    private function loadController():void {
        if (file_exists("../src/controller/controller.php")){
            $this->controller = new Controller($this->id,$this->entity);
        }else{
            $this->controller = new PageNotFoundController();
        }
    }
    private function loadEntity():void
    {
        $this->entity = $this->request[1] ?? null;
    }
    private function loadMethod():void {
        $this->method = $this->request[2] ?? null;
    }
    private function loadId():void {
        $this->id = $this->request[3] ?? null;
    }

    public function loadApp():void
    {
        $this->loadRequest();
        $this->loadMethod();
        $this->loadEntity();
        $this->loadId();

        if ($this->entity==null) {
            $this->entity = "users";
            $this->method = "showMainpage";
        }
        $this->loadController();
        $this->controller->{$this->method}();
    }


}