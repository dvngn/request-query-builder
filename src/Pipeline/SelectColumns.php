<?php

namespace Devengine\RequestQueryBuilder\Pipeline;

use Devengine\RequestQueryBuilder\Contracts\RequestQueryBuilderPipe;
use Devengine\RequestQueryBuilder\Models\BuildQueryParameters;

class SelectColumns implements RequestQueryBuilderPipe
{
    public function __invoke(BuildQueryParameters $parameters): void
    {
        [$request, $builder] = [$parameters->getRequest(), $parameters->getBuilder()];

        $allowedSelectFields = $parameters->getAllowedSelectFields();

        $selectFieldParameterName = $parameters->getSelectFieldsParameterName();

        $selectFieldsFromRequest = $this->parseRequestValue($request->input($selectFieldParameterName));

        $selectFields = array_values(array_intersect($allowedSelectFields, $selectFieldsFromRequest));

        if (empty($selectFields)) {
            return;
        }

        $builder->select($selectFields);
    }

    protected function parseRequestValue($value): array
    {
        if (is_string($value)) {
            return explode(',', $value);
        }

        if (is_array($value)) {
            return $value;
        }

        return [];
    }
}