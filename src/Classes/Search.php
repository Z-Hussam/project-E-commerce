<?php

namespace App\Classes;

use App\Entity\Category;

class Search
{
    /**
     * Summary of string
     * @var string
     */
    public $string = '';

    /**
     * Summary of category
     * @var Category[]
     */
    public $categories = [];


    public $min = '';
    public $max = '';
    public $filter = ''; 
}
