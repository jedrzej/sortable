<?php

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Sortable\SortableTrait;

class TestModel extends Model
{
    use SortableTrait;

    protected function newBaseQueryBuilder()
    {
        return new TestBuilder;
    }
}