<?php
interface CacheItem
{
    public function GetKey();
    public function Get();
    public function IsHit();
    public function Set($data);
    public function ExpiresAt($timeStamp);
    public function ExpiresAfter($time);
}