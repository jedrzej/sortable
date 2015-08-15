<?php

class TestModelWithSortableMethod extends TestModel
{
    /**
     * Returns list of sortable fields
     *
     * @return array
     */
    public function getSortableAttributes()
    {
        return ['field1', 'field2'];
    }
}