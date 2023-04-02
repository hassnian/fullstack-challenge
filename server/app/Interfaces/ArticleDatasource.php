<?php

namespace App\Interfaces;
interface ArticleDatasource
{
    public function getArticles($query = ''): array;
}
