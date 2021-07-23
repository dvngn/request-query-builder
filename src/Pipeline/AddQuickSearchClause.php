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
        $request = $parameters->getRequest();
        $builder = $parameters->getBuilder();
        $searchQuery = $request->input('search');
        $quickSearchFields = $parameters->getQuickSearchFields();

        if (empty($quickSearchFields)) {
            return;
        }

        if (!is_string($searchQuery) || trim($searchQuery) === '') {
            return;
        }

        $searchQuery = addcslashes($searchQuery, '%_\\');

        $builder->where(function (Builder $builder) use ($quickSearchFields, $searchQuery) {

            foreach ($quickSearchFields as $fieldName) {
                $builder->orWhere($fieldName, 'like', "%$searchQuery%");
            }

        });
    }
}