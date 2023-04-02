<?php

namespace App\Helpers;

class ArticleCategoryData
{
    public function __construct(
        public string $id,
        public string $name,
    )
    {
    }
}