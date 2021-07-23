<?php

namespace Devengine\RequestQueryBuilder\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final class BuildQueryParameters
{
    protected Builder $builder;

    protected Request $request;

    protected array $allowedOrderFields = [];

    protected array $allowedOrderFieldDictionary = [];

    protected array $allowedSelectFields = [];

    protected array $quickSearchFields = [];

    protected ?OrderParameters $defaultOrder = null;

    protected string $sortParameterPrefix = 'order_by_';

    protected string $selectFieldsParameterName = 'fields';

    /**
     * BuildQueryParameters constructor.
     * @param Builder $builder
     * @param Request $request
     */
    public function __construct(Builder $builder, Request $request)
    {
        $this->builder = $builder;
        $this->request = $request;
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

    /**
     * @return array
     */
    public function getAllowedSelectFields(): array
    {
        return $this->allowedSelectFields;
    }

    /**
     * @param array $allowedSelectFields
     */
    public function setAllowedSelectFields(array $allowedSelectFields): void
    {
        $this->allowedSelectFields = $allowedSelectFields;
    }

    /**
     * @return string
     */
    public function getSelectFieldsParameterName(): string
    {
        return $this->selectFieldsParameterName;
    }

    /**
     * @return array
     */
    public function getAllowedOrderFieldDictionary(): array
    {
        return $this->allowedOrderFieldDictionary;
    }

    /**
     * @param array $allowedOrderFieldDictionary
     */
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
}