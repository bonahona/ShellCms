<?php
require_once('/ShellLib/Core/ModelCollection.php');
class Models
{
    protected $ModelCollections;

    public function __construct()
    {
        $this->ModelCollections = array();
    }

    public function __get($modelName)
    {
        if(array_key_exists($modelName, $this->ModelCollections)){
            return $this->ModelCollections[$modelName];
        }else{
            die("Model $modelName does not exists");
        }
    }

    public function Setup($modelCaches)
    {
        foreach($modelCaches as $modelName => $modelCache){
            $modelCollection = new ModelCollection();
            $modelCollection->ModelName = $modelName;
            $modelCollection->ModelCache = $modelCache;
            $this->AddModel($modelName, $modelCollection);
        }
    }

    public function AddModel($modelName, $model){
        if(isset($this->ModelCollections[$modelName])){
            return false;
        }

        $this->ModelCollections[$modelName] = $model;
    }
}