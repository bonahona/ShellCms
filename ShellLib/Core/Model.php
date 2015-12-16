<?php
class Model
{
    protected $ModelCollection;
    protected $IsSaved;
    protected $IsDirty;
    protected $Properties;          // Properties matched in db
    protected $References;          // Model proxy objects for references
    protected $CustomProperties;    // Custom properties added from without the db. Wont be saved back

    function __construct($modelCollection)
    {
        // When called the model data is being cached from db, no model collection will be sent in as it only needs the table name
        if($modelCollection == null){
            return;
        }

        $this->ModelCollection = $modelCollection;

        $this->Properties = array();
        foreach($modelCollection->ModelCache['Columns'] as $key => $column){
            $this->Properties[$column['Field']] = $column['Default'];
        }

        $this->CustomProperties = array();

        $this->IsSaved = false;
        $this->IsDirty = false;
    }

    public function Validate()
    {
        return array();
    }

    public function HasProperty($name)
    {
        return isset($this->Properties[$name]);
    }

    function FlagAsSaved()
    {
        $this->IsSaved = true;
    }

    function FlagAsClean()
    {
        $this->IsDirty = false;
    }

    function __get($propertyName)
    {
        if(array_key_exists($propertyName, $this->Properties)) {
            return $this->Properties[$propertyName];
        }else if(array_key_exists($propertyName, $this->CustomProperties)){
            return $this->CustomProperties[$propertyName];
        }else{
            return null;
        }
    }

    function __set($propertyName, $value)
    {
        if(array_key_exists($propertyName, $this->Properties)){
            $this->Properties[$propertyName] = $value;
            $this->IsDirty = true;
        }else{
            $this->CustomProperties[$propertyName] = $value;
        }
    }

    public function IsSaved()
    {
        return $this->IsSaved;
    }

    public function IsDirty()
    {
        return $this->IsDirty;
    }

    public function Save()
    {
        $this->ModelCollection->Save($this);
        $this->FlagAsSaved();
        $this->FlagAsClean();
    }

    public function Delete()
    {
        $this->ModelCollection->Delete($this);
    }
}