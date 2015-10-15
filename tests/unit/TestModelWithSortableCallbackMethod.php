<?php

class TestModelWithSortableCallbackMethod extends TestModel
{
    protected $sortable = ['name'];

    /**
     * Sorts by the "real_name" field.  Accessed by sorting "name".
     *
     * @param $query
     * @param string $direction
     * @return mixed
     */
    public function sortName($query, $direction = 'desc')
    {
        return $query->orderBy('real_name', $direction);
    }
}
