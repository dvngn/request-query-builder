<?php

namespace Devengine\RequestQueryBuilder\Pipeline;

use Devengine\RequestQueryBuilder\Contracts\RequestQueryBuilderPipe;
use Devengine\RequestQueryBuilder\Models\BuildQueryParameters;

class AddOrderBy implements RequestQueryBuilderPipe
{
    protected array $allowedValues = ['asc', 'ASC', 'desc', 'DESC'];

    public function __invoke(BuildQueryParameters $parameters): void
    {
        $allowedOrderFields = $parameters->getAllowedOrderFields();
        $allowedOrderFieldDictionary = $parameters->getAllowedOrderFieldDictionary();
        $prefix = $parameters->getSortParameterPrefix();
        $request = $parameters->getRequest();
        $builder = $parameters->getBuilder();
        $defaultOrder = $parameters->getDefaultOrder();

        $appliedOrder = [];

        foreach ($allowedOrderFields as $orderFieldName) {
            $qualifiedRequestParameter = $prefix.$orderFieldName;

            if (false === $request->has($qualifiedRequestParameter)) {
                continue;
            }

            $translatedFieldName = $allowedOrderFieldDictionary[$orderFieldName] ?? $orderFieldName;

            $requestParameterValue = $request->input($qualifiedRequestParameter);

            if (false === $this->validateRequestValue($requestParameterValue)) {
                continue;
            }

            $builder->orderBy($translatedFieldName, $requestParameterValue);

            $appliedOrder[$orderFieldName] = $requestParameterValue;
        }

        if (empty($appliedOrder) && !is_null($defaultOrder)) {
            $builder->orderBy($defaultOrder->getColumnName(), $defaultOrder->getOrderDirection());
        }
    }

    protected function validateRequestValue(mixed $value): bool
    {
        return is_string($value) &&
            in_array($value, $this->allowedValues, true);
    }
}