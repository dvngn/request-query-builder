<?php

namespace Devengine\RequestQueryBuilder\Tests;

use Devengine\RequestQueryBuilder\RequestQueryBuilder;
use Devengine\RequestQueryBuilder\Tests\Models\TestModel;
use Illuminate\Http\Request;

class RequestQueryBuilderTest extends TestCase
{
    public function test_it_can_be_instantiated_from_eloquent_builder_and_request()
    {
        $query = TestModel::query();
        $request = new Request();

        $builder = RequestQueryBuilder::for(
            builder: $query,
            request: $request
        );

        $this->assertInstanceOf(RequestQueryBuilder::class, $builder);
    }
}