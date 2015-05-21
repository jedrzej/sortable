<?php namespace Jedrzej\Sortable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

trait SortableTrait
{
    /**
     * Should return list of sortable fields.
     *
     * @return array
     */
    abstract public function getSortableAttributes();

    /**
     * Applies filters.
     *
     * @param Builder $builder query builder
     * @param array   $query   query parameters to use for sorting - Input::all() is used by default
     */
    public function scopeSorted(Builder $builder, array $query = [])
    {
        $query = $query ?: Input::all();

        $criteria = $this->getCriteria($builder, (array)array_get($query, 'sort', []));
        $this->applyCriteria($builder, $criteria);
    }

    /**
     * Builds sort criteria based on model's sortable fields and query parameters.
     *
     * @param Builder $builder query builder
     * @param array   $query   query parameters
     *
     * @return array
     */
    protected function getCriteria(Builder $builder, array $query)
    {
        $criteria = [];
        foreach ($query as $value) {
            $criterion = Criterion::make($value);
            if ($this->isFieldSortable($builder, $criterion->getField())) {
                $criteria[] = $criterion;
            }
        }

        return $criteria;
    }

    /**
     * Check if field is sortable for given model.
     *
     * @param Builder $builder query builder
     * @param string  $field   field name
     *
     * @return bool
     */
    protected function isFieldSortable(Builder $builder, $field)
    {
        $sortable = $builder->getModel()->getSortableAttributes();

        return in_array($field, $sortable);
    }

    /**
     * Applies criteria to query
     *
     * @param Builder     $builder  query builder
     * @param Criterion[] $criteria sorting criteria
     */
    protected function applyCriteria(Builder $builder, array $criteria)
    {
        foreach ($criteria as $criterion) {
            $criterion->apply($builder);
        }
    }
}
