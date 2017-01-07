<?php
interface ICacheItemPool
{
    public function GetItem($key);
    public function GetItems($keys);
    public function HasItem($key);
    public function Clear();
    public function DeleteItem($key);
    public function DeleteItems($keys);
    public function Save($item);
    public function SaveDeferred($item);
    public function Commit();
}