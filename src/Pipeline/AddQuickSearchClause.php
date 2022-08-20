<?php

namespace Devengine\RequestQueryBuilder\Pipeline;

use Devengine\RequestQueryBuilder\Contracts\RequestQueryBuilderPipe;
use Devengine\RequestQueryBuilder\Models\BuildQueryParameters;
use Illuminate\Database\Eloquent\Builder;

class AddQuickSearchClause implements RequestQueryBuilderPipe
{
    protected string $quickSearchParameterName = 'search';

    public function __invoke(BuildQueryParameters $parameters): void
    {
        [$request, $builder] = [$parameters->getRequest(), $parameters->getBuilder()];

        $searchQueryProcessor = $parameters->getSearchQueryProcessor();

        $searchQuery = $request->input($this->quickSearchParameterName);

        $quickSearchFields = $parameters->getQuickSearchFields();

        if (empty($quickSearchFields)) {
            return;
        }

        if (!is_string($searchQuery) || trim($searchQuery) === '') {
            return;
        }

        $builder->where(function (Builder $builder) use ($searchQueryProcessor, $quickSearchFields, $searchQuery) {
            foreach ($quickSearchFields as $fieldName) {
                $builder->orWhere($fieldName, 'like', $searchQueryProcessor($searchQuery, $fieldName));
            }
        });
    }
}