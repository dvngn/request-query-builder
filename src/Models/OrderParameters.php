<?php

namespace Devengine\RequestQueryBuilder\Models;

final class OrderParameters
{
    protected string $columnName;

    protected string $orderDirection;

    /**
     * OrderParameters constructor.
     * @param string $columnName
     * @param string $orderDirection
     */
    public function __construct(string $columnName, string $orderDirection)
    {
        $this->columnName = $columnName;
        $this->orderDirection = $orderDirection;
    }

    public function getColumnName(): string
    {
        return $this->columnName;
    }

    public function getOrderDirection(): string
    {
        return $this->orderDirection;
    }
}