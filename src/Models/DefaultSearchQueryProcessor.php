<?php

namespace Devengine\RequestQueryBuilder\Models;

use Devengine\RequestQueryBuilder\Contracts\SearchQueryProcessor;

class DefaultSearchQueryProcessor implements SearchQueryProcessor
{
    public function __invoke(string $query, string $fieldName): string
    {
        $query = trim($query);
        $query = addcslashes($query, '%_\\');

        return "%$query%";
    }
}