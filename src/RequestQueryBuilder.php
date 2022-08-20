<?php

namespace Devengine\RequestQueryBuilder;

use Devengine\Exceptions\FieldOrderException;
use Devengine\RequestQueryBuilder\Contracts\{RequestQueryBuilderPipe, SearchQueryProcessor};
use Devengine\RequestQueryBuilder\Models\{BuildQueryParameters, OrderParameters};
use Devengine\RequestQueryBuilder\Pipeline\{AddOrderBy, AddQuickSearchClause, SelectColumns};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Pure;

class RequestQueryBuilder
{
    /** @var RequestQueryBuilderPipe[] */
    protected array $customBuildQueryPipes = [];

    #[Pure]
    public function __construct(protected BuildQueryParameters $buildParameters)
    {
    }

    #[Pure]
    public static function for(Builder $builder, Request $request): static
    {
        return new static(
            buildParameters: new BuildQueryParameters(
                builder: $builder,
                request: $request
            )
        );
    }

    public function allowOrderFields(string ...$fields): static
    {
        return tap($this, function () use ($fields): void {
            $this->buildParameters->setAllowedOrderFields($fields);
        });
    }

    public function qualifyOrderFields(string ...$fields): static
    {
        return tap($this, function () use ($fields): void {
            $this->buildParameters->setOrderFieldDictionary($fields);
        });
    }

    /** @deprecated use {@link qualifyOrderFields()} instead */
    public function translateOrderFields(array $dictionary): static
    {
        return tap($this, function () use ($dictionary): void {
            $this->buildParameters->setOrderFieldDictionary($dictionary);
        });
    }

    public function allowSelectFields(string ...$fields): static
    {
        return tap($this, function () use ($fields): void {
            $this->buildParameters->setAllowedSelectFields($fields);
        });
    }

    public function allowQuickSearchFields(string ...$fields): static
    {
        return tap($this, function () use ($fields) {
            $this->buildParameters->setQuickSearchFields($fields);
        });
    }

    public function enforceOrderBy(string $column, string $direction = 'desc'): static
    {
        if (false === in_array($direction, ['asc', 'desc'], true)) {
            throw FieldOrderException::invalidOrderDirection($direction);
        }

        return tap($this, function () use ($column, $direction) {
            $this->buildParameters->setDefaultOrder(
                new OrderParameters($column, $direction)
            );
        });
    }

    public function addCustomBuildQueryPipe(RequestQueryBuilderPipe ...$pipe): static
    {
        return tap($this, function () use ($pipe): void {
            $this->customBuildQueryPipes = [...$this->customBuildQueryPipes, ...$pipe];
        });
    }

    public function processSearchQueryWith(SearchQueryProcessor $processor): static
    {
        return tap($this, function () use ($processor): void {
            $this->buildParameters->setSearchQueryProcessor($processor);
        });
    }

    public function process(): Builder
    {
        return tap($this->buildParameters->getBuilder(), function () {
            foreach (array_merge($this->getDefaultBuildQueryPipeline(), $this->customBuildQueryPipes) as $pipe) {
                $pipe($this->buildParameters);
            }
        });
    }

    #[Pure]
    public function getDefaultBuildQueryPipeline(): array
    {
        return [
            new AddOrderBy,
            new SelectColumns,
            new AddQuickSearchClause,
        ];
    }

    /**
     * @return BuildQueryParameters
     */
    public function getBuildParameters(): BuildQueryParameters
    {
        return $this->buildParameters;
    }

    /**
     * @return RequestQueryBuilderPipe[]
     */
    public function getCustomBuildQueryPipes(): array
    {
        return $this->customBuildQueryPipes;
    }
}