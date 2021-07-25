<?php

namespace Devengine\RequestQueryBuilder\Models;

final class OrderParameters
{
    public function __construct(protected string $columnName,
                                protected string $orderDirection)
    {
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