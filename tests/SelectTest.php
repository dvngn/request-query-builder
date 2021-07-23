<?php

namespace Devengine\RequestQueryBuilder\Tests;

use Devengine\RequestQueryBuilder\RequestQueryBuilder;
use Devengine\RequestQueryBuilder\Tests\Models\TestModel;
use Illuminate\Http\Request;

class SelectTest extends TestCase
{
    public function test_it_can_select_fields_from_a_query()
    {
        $query = TestModel::query();
        $request = new Request([
            'fields' => [
                'id',
                'created_at',
            ],
        ]);

        $builder = RequestQueryBuilder::for(
            $query,
            $request,
        )
            ->allowSelectFields(...[
                'id',
                'created_at'
            ])
            ->process();

        $this->assertCount(2, $builder->getQuery()->columns);

        $this->assertContains('id', $builder->getQuery()->columns);
        $this->assertContains('created_at', $builder->getQuery()->columns);
    }
}