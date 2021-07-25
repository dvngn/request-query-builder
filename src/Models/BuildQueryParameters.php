<?php

namespace Devengine\RequestQueryBuilder\Models;

use Devengine\RequestQueryBuilder\Contracts\SearchQueryProcessor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Pure;

final class BuildQueryParameters
{
    protected array $allowedOrderFields = [];

    protected array $allowedOrderFieldDictionary = [];

    protected array $allowedSelectFields = [];

    protected array $quickSearchFields = [];

    protected ?OrderParameters $defaultOrder = null;

    protected string $sortParameterPrefix = 'order_by_';

    protected string $selectFieldsParameterName = 'fields';

    protected SearchQueryProcessor $searchQueryProcessor;

    #[Pure]
    public function __construct(protected Builder $builder, protected Request $request)
    {
        $this->searchQueryProcessor = new DefaultSearchQueryProcessor();
    }

    public function getBuilder(): Builder
    {
        return $this->builder;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getAllowedOrderFields(): array
    {
        return $this->allowedOrderFields;
    }

    public function setAllowedOrderFields(array $allowedOrderFields): void
    {
        $this->allowedOrderFields = $allowedOrderFields;
    }

    public function getSortParameterPrefix(): string
    {
        return $this->sortParameterPrefix;
    }

    public function setSortParameterPrefix(string $sortParameterPrefix): void
    {
        $this->sortParameterPrefix = $sortParameterPrefix;
    }

    public function getDefaultOrder(): ?OrderParameters
    {
        return $this->defaultOrder;
    }


    public function setDefaultOrder(?OrderParameters $parameters): void
    {
        $this->defaultOrder = $parameters;
    }

    public function getAllowedSelectFields(): array
    {
        return $this->allowedSelectFields;
    }

    public function setAllowedSelectFields(array $allowedSelectFields): void
    {
        $this->allowedSelectFields = $allowedSelectFields;
    }

    public function getSelectFieldsParameterName(): string
    {
        return $this->selectFieldsParameterName;
    }

    public function getAllowedOrderFieldDictionary(): array
    {
        return $this->allowedOrderFieldDictionary;
    }

    public function setOrderFieldDictionary(array $allowedOrderFieldDictionary): void
    {
        $this->allowedOrderFieldDictionary = $allowedOrderFieldDictionary;
    }

    public function setQuickSearchFields(array $fields): void
    {
        $this->quickSearchFields = $fields;
    }

    public function getQuickSearchFields(): array
    {
        return $this->quickSearchFields;
    }

    public function getSearchQueryProcessor(): SearchQueryProcessor
    {
        return $this->searchQueryProcessor;
    }

    public function setSearchQueryProcessor(SearchQueryProcessor $searchQueryProcessor): void
    {
        $this->searchQueryProcessor = $searchQueryProcessor;
    }
}