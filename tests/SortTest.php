<?php

namespace Devengine\RequestQueryBuilder\Tests;

use Devengine\RequestQueryBuilder\RequestQueryBuilder;
use Devengine\RequestQueryBuilder\Tests\Models\TestModel;
use Illuminate\Http\Request;

class SortTest extends TestCase
{
    public function test_it_can_sort_a_query()
    {
        $query = TestModel::query();
        $request = new Request([
            'order_by_created_at' => 'desc',
        ]);

        $builder = RequestQueryBuilder::for(
            $query,
            $request,
        )
            ->allowOrderFields(...[
                'created_at',
                'updated_at'
            ])
            ->process();

        $this->assertCount(1, $builder->getQuery()->orders);

        $this->assertSame([
            'column' => 'created_at',
            'direction' => 'desc',
        ], $builder->getQuery()->orders[0]);
    }

    public function test_it_can_sort_a_query_by_dictionary()
    {
        $query = TestModel::query();
        $request = new Request([
            'order_by_created_at' => 'desc',
        ]);

        $builder = RequestQueryBuilder::for(
            $query,
            $request,
        )
            ->allowOrderFields(...[
                'created_at',
                'updated_at'
            ])
            ->qualifyOrderFields(
                created_at: 'test_models_join.created_at',
            )
            ->process();

        $this->assertCount(1, $builder->getQuery()->orders);

        $this->assertSame([
            'column' => 'test_models_join.created_at',
            'direction' => 'desc',
        ], $builder->getQuery()->orders[0]);
    }
}