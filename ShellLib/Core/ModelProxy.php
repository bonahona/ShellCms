<?php
// Class built to act as a proxy for models containing foreign keys to other objects.
// This is built to whenever a model with references is loaded it does not have to actually load all other obejcst from db
// but their info is stored so they can be loaded upon request
class ModelProxy
{
    protected $m_primaryKey;
    protected $m_model;
    protected  $m_object;

    function __construct($primaryKey, $model)
    {
        $this->m_primaryKey = $primaryKey;
        $this->m_model = $model;
        $this->m_object = null;
    }

    public function Load()
    {
        if($this->m_object == null){
            $this->m_object = $this->m_model->Find($this->m_primaryKey);
        }
    }
}