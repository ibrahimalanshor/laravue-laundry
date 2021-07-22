<?php 

namespace App\Repositories;

abstract class Repository
{

    public $model;

    public function __construct()
    {
        $this->setModel();
    }

    private function setModel()
    {
        $this->model = app()->make($this->getModel());
    }

    abstract protected function getModel(): String;

}

 ?>