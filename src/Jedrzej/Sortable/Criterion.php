<?php namespace Jedrzej\Sortable;

use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;
use Illuminate\Support\Str;

class Criterion
{
    const ORDER_ASCENDING = 'asc';
    const ORDER_DESCENDING = 'desc';

    protected $field;

    protected $order;

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Creates criterion object for given value.
     *
     * @param string $value query value
     * @param string $defaultOrder default sort order if order is not given explicitly in query
     *
     * @return Criterion
     */
    public static function make($value, $defaultOrder = self::ORDER_ASCENDING)
    {
        $value = static::prepareValue($value);
        list($field, $order) = static::parseFieldAndOrder($value, $defaultOrder);

        static::validateFieldName($field);

        return new static($field, $order);
    }

    /**
     * Applies criterion to query.
     *
     * @param Builder $builder query builder
     */
    public function apply(Builder $builder)
    {
        $sortMethod = 'sort' . Str::studly($this->getField());

        if(method_exists($builder->getModel(), $sortMethod)) {
            call_user_func_array([$builder->getModel(), $sortMethod], [$builder, $this->getOrder()]);
        } else {
            $builder->orderBy($this->getField(), $this->getOrder());
        }
    }

    /**
     * @param string $field field name
     * @param string $order sort order
     */
    protected function __construct($field, $order)
    {
        if (!in_array($order, [static::ORDER_ASCENDING, static::ORDER_DESCENDING])) {
            throw new InvalidArgumentException('Invalid order value');
        }

        $this->field = $field;
        $this->order = $order;
    }

    /**
     * Makes sure field names contain only allowed characters
     *
     * @param string $fieldName
     */
    protected static function validateFieldName($fieldName) {
        if (!preg_match('/^[a-zA-Z0-9\-_:\.]+$/', $fieldName)) {
            throw new InvalidArgumentException(sprintf('Incorrect field name: %s', $fieldName));
        }
    }

    /**
     *  Cleans value and converts to array if needed.
     *
     * @param string $value value
     *
     * @return string
     */
    protected static function prepareValue($value)
    {
        return trim($value, " \t\n\r\0\x0B");
    }

    /**
     * Parse query parameter and get field name and order.
     *
     * @param string $value
     * @param string $defaultOrder default sort order if order is not given explicitly in query
     *
     * @return string[]
     *
     * @throws InvalidArgumentException when unable to parse field name or order
     */
    protected static function parseFieldAndOrder($value, $defaultOrder)
    {
        if (preg_match('/^([^,]+)(,(asc|desc))?$/', $value, $match)) {
            return [$match[1], isset($match[3]) ? $match[3] : $defaultOrder];

        }

        throw new InvalidArgumentException(sprintf('Unable to parse field name or order from "%s"', $value));
    }
}
