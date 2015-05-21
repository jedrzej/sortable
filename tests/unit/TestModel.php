<?php

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Sortable\SortableTrait;

class TestModel extends Model
{
    use SortableTrait;

    /**
     * Returns list of sortable fields
     *
     * @return array
     */
    public function getSortableAttributes()
    {
        return ['field1', 'field2'];
    }

    protected function newBaseQueryBuilder()
    {
        return new TestBuilder;
    }
}