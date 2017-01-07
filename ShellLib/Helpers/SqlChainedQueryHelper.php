<?php
class SqlChainedQueryHelper
{
    /* @var ModelCollection */
    public $ModelCollection;

    /* @var DatabaseWhereCondition */
    public $WhereClause;
    
    /* @var int */
    public $Take;

    /* @var String */
    public $OrderByColumn;

    /* @var SqlChainedQueryHelper */
    public $SubQuery;

    public function __construct($modelCollection, $options = array())
    {
        $this->ModelCollection = $modelCollection;
        $this->AddOptions($options);
    }

    public function AddOptions($options = array()){
        foreach($options as $key => $value){
            if($key == 'where'){
                $this->WhereClause = $value;
            }else if($key == 'take'){
                $this->Take = $value;
            } else if($key == 'orderBy'){
                $this->OrderByColumn = $value;
            }else{
                trigger_error('Unknown Sql options:' . $key, E_USER_NOTICE);
            }
        }
    }
}