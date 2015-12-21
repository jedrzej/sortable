<?php

class TestModelWithDefaultSortingCriteria extends TestModelWithAllFieldsSortable
{
    protected $defaultSortCriteria = [
        'column1,desc',
        'column2,asc'
    ];
}