<?php

namespace Devengine\RequestQueryBuilder;

use Devengine\RequestQueryBuilder\Contracts\RequestQueryBuilderPipe;
use Devengine\RequestQueryBuilder\Models\BuildQueryParameters;
use Devengine\RequestQueryBuilder\Models\OrderParameters;
use Devengine\RequestQueryBuilder\Pipeline\AddOrderBy;
use Devengine\RequestQueryBuilder\Pipeline\AddQuickSearchClause;
use Devengine\RequestQueryBuilder\Pipeline\SelectColumns;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Pure;

class RequestQueryBuilder
{
    protected BuildQueryParameters $builderParameters;

    /** @var RequestQueryBuilderPipe[] */
    protected array $customBuildQueryPipes = [];

    #[Pure]
    public function __construct(Builder $builder, Request $request)
    {
        $this->builderParameters = new BuildQueryParameters(
            builder: $builder,
            request: $request
        );
    }

    #[Pure]
    public static function for(Builder $builder, Request $request): static
    {
        return new static(
            builder: $builder,
            request: $request
        );
    }

    public function allowOrderFields(string ...$fields): static
    {
        return tap($this, function () use ($fields) {
            $this->builderParameters->setAllowedOrderFields($fields);
        });
    }

    public function translateOrderFields(array $dictionary): static
    {
        return tap($this, function () use ($dictionary) {
            $this->builderParameters->setOrderFieldDictionary($dictionary);
        });
    }

    public function allowSelectFields(string ...$fields): static
    {
        return tap($this, function () use ($fields) {
            $this->builderParameters->setAllowedSelectFields($fields);
        });
    }

    public function allowQuickSearchFields(string ...$fields): static
    {
        return tap($this, function () use ($fields) {
            $this->builderParameters->setQuickSearchFields($fields);
        });
    }

    public function enforceOrderBy(string $columnName, string $orderDirection): static
    {
        return tap($this, function () use ($columnName, $orderDirection) {
            $this->builderParameters->setDefaultOrder(
                new OrderParameters($columnName, $orderDirection)
            );
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

    public function addCustomBuildQueryPipe(RequestQueryBuilderPipe ...$pipe): self
    {
        return tap($this, function () use ($pipe) {
            $this->customBuildQueryPipes = [...$this->customBuildQueryPipes, ...$pipe];
        });
    }

    public function process(): Builder
    {
        return tap($this->builderParameters->getBuilder(), function () {

            foreach (array_merge($this->getDefaultBuildQueryPipeline(), $this->customBuildQueryPipes) as $pipe) {
                $pipe($this->builderParameters);
            }

        });
    }

    /**
     * @return BuildQueryParameters
     */
    public function getBuilderParameters(): BuildQueryParameters
    {
        return $this->builderParameters;
    }

    /**
     * @return RequestQueryBuilderPipe[]
     */
    public function getCustomBuildQueryPipes(): array
    {
        return $this->customBuildQueryPipes;
    }
}