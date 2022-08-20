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

        $searchQuery = $request->input($this->quickSearchParameterName);

        if (empty($parameters->getQuickSearchFields())) {
            return;
        }

        if (!is_string($searchQuery) || trim($searchQuery) === '') {
            return;
        }

        $builder->where(static function (Builder $builder) use ($searchQuery, $parameters): void {
            foreach ($parameters->getQuickSearchFields() as $fieldName) {
                $builder->orWhere($fieldName, 'like', $parameters->getSearchQueryProcessor()($searchQuery, $fieldName));
            }
        });
    }
}