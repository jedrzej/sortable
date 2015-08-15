<?php

class TestModelWithAllFieldsSortable extends TestModel
{
    protected $sortable = ['field1', '*'];
}