<?php

class TestModelWithSortableCallbackMethod extends TestModel
{
    protected $sortable = ['created_at'];

    /**
     * Sorts by the "created" field.  Accessed by sorting "created_at".
     *
     * @param $query
     * @param string $direction
     * @return mixed
     */
    public function sortCreatedAt($query, $direction = 'desc')
    {
        return $query->orderBy('created', $direction);
    }
}
