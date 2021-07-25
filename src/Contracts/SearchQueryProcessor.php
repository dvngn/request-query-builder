<?php

namespace Devengine\RequestQueryBuilder\Contracts;

interface SearchQueryProcessor
{
    public function __invoke(string $query, string $fieldName): string;
}