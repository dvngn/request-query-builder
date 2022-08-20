<?php

namespace Devengine\RequestQueryBuilder\Pipeline;

use Devengine\RequestQueryBuilder\Contracts\RequestQueryBuilderPipe;
use Devengine\RequestQueryBuilder\Models\BuildQueryParameters;

class SelectColumns implements RequestQueryBuilderPipe
{
    public function __invoke(BuildQueryParameters $parameters): void
    {
        [$request, $builder] = [$parameters->getRequest(), $parameters->getBuilder()];

        $selectFieldsFromRequest = $this->parseRequestValue(
            $request->input($parameters->getSelectFieldsParameterName())
        );

        $selectFields = array_values(array_intersect($parameters->getAllowedSelectFields(), $selectFieldsFromRequest));

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