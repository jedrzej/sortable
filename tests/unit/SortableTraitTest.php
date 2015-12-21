<?php

use Codeception\Specify;
use Codeception\TestCase\Test;
use Illuminate\Support\Facades\Input;

class SortableTraitTest extends Test
{
    use Specify;

    public function testCriteria()
    {
        $this->specify("sort criterion is applied when only one is given", function () {
            $this->assertCount(1, (array)TestModelWithSortableMethod::sorted(['sort' => 'field1,asc'])->getQuery()->orders);
        });

        $this->specify("sort criteria are applied when array is given", function () {
            $this->assertCount(2, (array)TestModelWithSortableMethod::sorted(['field1,asc', 'field2,desc'])->getQuery()->orders);
        });       $this->specify("sort criterion is applied when only one is given", function () {
            $this->assertCount(1, (array)TestModelWithSortableMethod::sorted(['sort' => 'field1,asc'])->getQuery()->orders);
        });

        $this->specify("sort criteria are applied when array is given", function () {
            $this->assertCount(2, (array)TestModelWithSortableMethod::sorted(['field1,asc', 'field2,desc'])->getQuery()->orders);
        });

        $this->specify("criteria are applied only to sortable parameters", function () {
            $this->assertCount(0, (array)TestModelWithSortableMethod::sorted('field0,asc')->getQuery()->orders);
            $this->assertCount(1, (array)TestModelWithSortableMethod::sorted(['field0,asc', 'field1,desc'])->getQuery()->orders);
            $this->assertCount(2, (array)TestModelWithSortableMethod::sorted(['sort' => ['field0,asc', 'field1,desc', 'field2,desc']])->getQuery()->orders);
            $this->assertCount(2, (array)TestModelWithSortableMethod::sorted(['field0,asc', 'field1,desc', 'field2,desc', 'field3,desc'])->getQuery()->orders);
        });

        $this->specify("criteria are applied to columns by name", function () {
            $criterion = (array)TestModelWithSortableMethod::sorted('field1,asc')->getQuery()->orders[0];
            $this->assertEquals('field1', $criterion['column']);
        });

        $this->specify("criteria are applied in the same order as specified", function () {
            $criteria = (array)TestModelWithSortableMethod::sorted(['field1,desc', 'field2,desc'])->getQuery()->orders;
            $this->assertEquals('field1', $criteria[0]['column']);
            $this->assertEquals('field2', $criteria[1]['column']);

            $criteria = (array)TestModelWithSortableMethod::sorted(['field2,desc', 'field1,desc'])->getQuery()->orders;
            $this->assertEquals('field2', $criteria[0]['column']);
            $this->assertEquals('field1', $criteria[1]['column']);
        });

        $this->specify('getSearchableAttribues is not required, if $searchable property exists', function() {
            $criteria = (array)TestModelWithSortableProperty::sorted(['field2,desc', 'field1,desc'])->getQuery()->orders;
            $this->assertEquals('field2', $criteria[0]['column']);
            $this->assertEquals('field1', $criteria[1]['column']);
        });

        $this->specify('model must implement getSortableAttributes() or have $sortable property', function() {
            TestModel::sorted(['field1,desc', 'field2,desc']);
        }, ['throws' => new RuntimeException]);

        $this->specify('* in searchable field list makes all fields searchable', function() {
            $criteria = (array)TestModelWithAllFieldsSortable::sorted(['field2,desc', 'field42,desc'])->getQuery()->orders;
            $this->assertEquals('field2', $criteria[0]['column']);
            $this->assertEquals('field42', $criteria[1]['column']);
        });

        $this->specify('available callback method is used in lieu of standard sorting', function() {
            $criteria = (array)TestModelWithSortableCallbackMethod::sorted(['created_at,desc'])->getQuery()->orders;
            $this->assertEquals('created', $criteria[0]['column']);
            $this->assertEquals('desc', $criteria[0]['direction']);
        });

        $this->specify('default sorting criteria are applued', function() {
            Input::shouldReceive('input')->andReturn(null);
            $criteria = (array)TestModelWithDefaultSortingCriteria::sorted()->getQuery()->orders;
            $this->assertEquals('column1', $criteria[0]['column']);
            $this->assertEquals('desc', $criteria[0]['direction']);
            $this->assertEquals('column2', $criteria[1]['column']);
            $this->assertEquals('asc', $criteria[1]['direction']);
        });
    }
}
