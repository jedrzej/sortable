<?php

use Jedrzej\Sortable\Criterion;

class TestModelWithDefaultSortOrder extends TestModel
{
    protected $sortable = ['*'];

    protected $defaultSortOrder = Criterion::ORDER_DESCENDING;
}