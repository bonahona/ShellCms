<?php

class HomeController extends Controller
{
    public function Index()
    {$this->Logging->Cache
        $this->Title = "Index";
        return $this->View();

    }
}