<?php

namespace App\Entity;

class Article
{
    public $path;

    public $name;

    public $headers = [];

    public function __construct($path)
    {
        $this->path = $path;
    }
}
