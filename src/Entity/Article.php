<?php

namespace App\Entity;

class Article
{
    public $path;

    public $name;

    public function __construct($path)
    {
        $this->path = $path;
    }
}
