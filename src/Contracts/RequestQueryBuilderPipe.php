<?php

namespace Devengine\RequestQueryBuilder\Contracts;

use Devengine\RequestQueryBuilder\Models\BuildQueryParameters;

interface RequestQueryBuilderPipe
{
    /**
     * Process the payload.
     *
     * @param BuildQueryParameters $parameters
     */
    public function __invoke(BuildQueryParameters $parameters): void;
}