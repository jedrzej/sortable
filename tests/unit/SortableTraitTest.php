<?php

use Codeception\Specify;
use Codeception\TestCase\Test;
use Jedrzej\Sortable\Criterion;

class SortableTraitTest extends Test
{
    use Specify;

    public function testCriteria()
    {
        $this->specify("sort criterion is applied when only one is given", function () {
            $this->assertCount(1, (array)TestModel::sorted(['sort' => 'field1,asc'])->getQuery()->orders);
        });

        $this->specify("sort criteria are applied when array is given", function () {
            $this->assertCount(2, (array)TestModel::sorted(['sort' => ['field1,asc', 'field2,desc']])->getQuery()->orders);
        });

        $this->specify("criteria are applied only to sortable parameters", function () {
            $this->assertCount(0, (array)TestModel::sorted(['sort' => 'field0,asc'])->getQuery()->orders);
            $this->assertCount(1, (array)TestModel::sorted(['sort' => ['field0,asc', 'field1,desc']])->getQuery()->orders);
            $this->assertCount(2, (array)TestModel::sorted(['sort' => ['field0,asc', 'field1,desc', 'field2,desc']])->getQuery()->orders);
            $this->assertCount(2, (array)TestModel::sorted(['sort' => ['field0,asc', 'field1,desc', 'field2,desc', 'field3,desc']])->getQuery()->orders);
        });

        $this->specify("criteria are applied to columns by name", function () {
            $criterion = (array)TestModel::sorted(['sort' => 'field1,asc'])->getQuery()->orders[0];
            $this->assertEquals('field1', $criterion['column']);
        });

        $this->specify("criteria are applied in the same order as specified", function () {
            $criteria = (array)TestModel::sorted(['sort' => ['field1,desc', 'field2,desc']])->getQuery()->orders;
            $this->assertEquals('field1', $criteria[0]['column']);
            $this->assertEquals('field2', $criteria[1]['column']);

            $criteria = (array)TestModel::sorted(['sort' => ['field2,desc', 'field1,desc']])->getQuery()->orders;
            $this->assertEquals('field2', $criteria[0]['column']);
            $this->assertEquals('field1', $criteria[1]['column']);
        });
    }
}
