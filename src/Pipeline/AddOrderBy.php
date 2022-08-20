<?php

namespace Devengine\RequestQueryBuilder\Pipeline;

use Devengine\RequestQueryBuilder\Contracts\RequestQueryBuilderPipe;
use Devengine\RequestQueryBuilder\Models\BuildQueryParameters;

class AddOrderBy implements RequestQueryBuilderPipe
{
    protected array $allowedValues = ['asc', 'ASC', 'desc', 'DESC'];

    public function __invoke(BuildQueryParameters $parameters): void
    {
        [$request, $builder] = [$parameters->getRequest(), $parameters->getBuilder()];

        $appliedOrder = [];

        foreach ($parameters->getAllowedOrderFields() as $orderFieldName) {
            $qualifiedRequestParameter = $parameters->getSortParameterPrefix().$orderFieldName;

            if ($request->has($qualifiedRequestParameter) === false) {
                continue;
            }

            $translatedFieldName = $parameters->getAllowedOrderFieldDictionary()[$orderFieldName] ?? $orderFieldName;

            $requestParameterValue = $request->input($qualifiedRequestParameter);

            if ($this->validateRequestValue($requestParameterValue) === false) {
                continue;
            }

            $builder->orderBy($translatedFieldName, $requestParameterValue);

            $appliedOrder[$orderFieldName] = $requestParameterValue;
        }

        if (empty($appliedOrder) && !is_null($parameters->getDefaultOrder())) {
            $builder->orderBy(
                $parameters->getDefaultOrder()->getColumnName(),
                $parameters->getDefaultOrder()->getOrderDirection()
            );
        }
    }

    protected function validateRequestValue(mixed $value): bool
    {
        return is_string($value) &&
            in_array($value, $this->allowedValues, true);
    }
}