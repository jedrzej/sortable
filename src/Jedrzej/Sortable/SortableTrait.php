<?php namespace Jedrzej\Sortable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Input;
use RuntimeException;

trait SortableTrait
{
    protected $sortParameterName = 'sort';

    protected $defaultSortCriteria = [];

    /**
     * Applies filters.
     *
     * @param Builder $builder query builder
     * @param array|string $query query parameters to use for sorting - $request[$this->sortParameterName] is used by default
     *                            with fallback to $this->defaultSortCriteria if $this->sortParameterName parameter is missing
     *                            in the request parameters
     */
    public function scopeSorted(Builder $builder, $query = [])
    {
        $query = (array)($query ?: Input::input($this->sortParameterName, $this->defaultSortCriteria));

        if (empty($query)) {
            $query = $this->defaultSortCriteria;
        }

        //unwrap sorting criteria array (for backwards compatibility)
        if (is_array($query) && array_key_exists($this->sortParameterName, $query)) {
            $query = (array)$query[$this->sortParameterName];
        }

        $criteria = $this->getCriteria($builder, $query);
        $this->applyCriteria($builder, $criteria);
    }

    /**
     * Builds sort criteria based on model's sortable fields and query parameters.
     *
     * @param Builder $builder query builder
     * @param array $query query parameters
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
     * @param string $field field name
     *
     * @return bool
     */
    protected function isFieldSortable(Builder $builder, $field)
    {
        $sortable = $this->_getSortableAttributes($builder);

        return in_array($field, $sortable) || in_array('*', $sortable);
    }

    /**
     * Applies criteria to query
     *
     * @param Builder $builder query builder
     * @param Criterion[] $criteria sorting criteria
     */
    protected function applyCriteria(Builder $builder, array $criteria)
    {
        foreach ($criteria as $criterion) {
            $criterion->apply($builder);
        }
    }

    /**
     * @param Builder $builder
     *
     * @return array list of sortable attributes
     */
    protected function _getSortableAttributes(Builder $builder)
    {
        if (method_exists($builder->getModel(), 'getSortableAttributes')) {
            return $builder->getModel()->getSortableAttributes();
        }

        if (property_exists($builder->getModel(), 'sortable')) {
            return $builder->getModel()->sortable;
        }

        throw new RuntimeException(sprintf('Model %s must either implement getSortableAttributes() or have $sortable property set', get_class($builder->getModel())));
    }
}
