<?php
class SqlCollection implements IDataCollection
{
    /* @var SqlChainedQueryHelper */
    public $ChainedQueryData;               // Stores data to help build the SQL query later on

    /* @var ICollection */
    private $m_queryCache;                  // Wen the combined query is executed, the resulting item is stored here

    /* @var int */
    private $m_position;                    // Internally used when iterating

    public function __construct()
    {
        $this->m_queryCache = null;
    }

    protected function GetItems()
    {
        if($this->m_queryCache == null){
            $this->m_queryCache = $this->ExecuteQuery();
        }

        return $this->m_queryCache;
    }

    /* @return ICollection */
    protected function ExecuteQuery()
    {
        $sqlStatement = $this->GetSqlQuery();
    }

    /* @return string*/
    protected  function GetSqlQuery()
    {
        return "";
    }

    public function Copy($items)
    {
        $this->m_queryCache = new Collection();
        foreach($items as $item){
            $this->m_queryCache->Add($item);
        }
    }

    function rewind()
    {
        $queryCache = $this->GetItems();
        $this->m_position = 0;
        $queryCache->rewind;
    }

    function current()
    {
        return $this->m_queryCache[$this->m_position];
    }

    function key()
    {
        return $this->m_position;
    }

    function next()
    {
        $this->m_position++;
    }

    function valid()
    {
        $queryCache = $this->GetItems();
        return isset($queryCache[$this->m_position]);
    }

    function count()
    {
        $queryCache = $this->GetItems();
        return count($queryCache);
    }

    public function Keys()
    {
        $queryCache = $this->GetItems();
        return $queryCache->Keys();
    }

    public function Add($item)
    {
        trigger_error('Add not allowed on SqlColletions', E_USER_ERROR);
    }

    public function OrderBy($field)
    {
        $this->ChainedQueryData->OrderByColumn = $field;
        return $this;
    }

    public function OrderByDescending($field)
    {
        trigger_error('Not implemented', E_USER_ERROR);
    }

    public function Where($conditions)
    {
        $this->ChainedQueryData->WhereClause = $conditions;
        return $this;
    }

    public function Any($conditions)
    {
        $this->ChainedQueryData->WhereClause = $conditions;
        $queryCache = $this->GetItems();

        if(count($queryCache) > 0){
            return true;
        } else{
            return false;
        }
    }

    public function Take($count)
    {
        $this->ChainedQueryData->Take = $count;
        return $this;
    }

    public function First()
    {
        $this->Take(1);
        $queryCache = $this->GetItems();

        if(count($queryCache) > 0){
            return $queryCache[0];
        }else{
            return null;
        }
    }

    public function Last()
    {
        $this->Take(1);
        $queryCache = $this->GetItems();

        if(count($queryCache) > 0){
            return $queryCache[count($queryCache) -1];
        }else{
            return null;
        }
    }

    public function ToArray()
    {
        trigger_error('SqlCollection::ToArray() not supported', E_USER_ERROR);
    }

    public function offsetSet($offset, $value)
    {
        trigger_error('Offset set not allowed for SqlCollection', E_USER_ERROR);
    }

    public function offsetExists($offset)
    {
        $queryCache = $this->GetItems();
        return isset($queryCache[$offset]);
    }

    public function offsetUnset($offset)
    {
        trigger_error('Offset unset not allowed for SqlCollection', E_USER_ERROR);
    }

    public function offsetGet($offset)
    {
        $queryCache = $this->GetItems();
        return $queryCache->offsetGet($offset);
    }
}