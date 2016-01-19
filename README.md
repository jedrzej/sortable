# Sortable trait for Laravel's Eloquent models

This package adds sorting functionality to Eloquent models in Laravel 4/5.

You could also find those packages useful:

- [Searchable](https://github.com/jedrzej/searchable) - Allows filtering your models using request parameters
- [Withable](https://github.com/jedrzej/withable) - Allows eager loading of relations using request parameters
- [Pimpable](https://github.com/jedrzej/pimpable) - A meta package that combines Sortable, Searchable and Withable behaviours

## Composer install

Add the following line to `composer.json` file in your project:

    "jedrzej/sortable": "0.0.8"

or run the following in the commandline in your project's root folder:

    composer require "jedrzej/sortable" "0.0.8"

## Setting up sortable models

In order to make an Eloquent model sortable, add the trait to the model and define a list of fields that the model can be sorted by.
You can either define a `$sortable` property or implement a `getSortableAttributes` method if you want to execute some logic to define
list of sortable fields.

```php
use Jedrzej\Sortable\SortableTrait;

class Post extends Eloquent
{
    use SortableTrait;

    // either a property holding a list of sortable fields...
    public $sortable = ['title', 'forum_id', 'created_at'];

    // ...or a method that returns a list of sortable fields
    public function getSortableAttributes()
    {
        return ['title', 'forum_id', 'created_at'];
    }
}
```

In order to make all fields sortable put an asterisk `*` in the list of sortable fields:

```php
public $sortable = ['*'];
```

## Sorting models

`SortableTrait` adds a `sorted()` scope to the model - you can pass it a query being an array of sorting conditions:

```php
// return all posts sorted by creation date in descending order
Post::sorted('created_at,desc')->get();

// return all users sorted by level in ascending order and then by points indescending orders
User::sorted(['level,asc', 'points,desc'])->get();
```
or it will use `Input::all()` as default:

    // return all posts sorted by creation date in descending order by appending to URL
    ?sort=created_at,desc
    //and then calling
    Post::sorted()->get();

    // return all users sorted by level in ascending order and then by points indescending orders by appending to URL
    ?sort[]=level,asc&sort[]=points,desc
    // and then calling
    User::sorted()->get();

## Overwriting default sorting logic

It is possible to overwrite how sorting parameters are used and applied to the query by implementing a callback in your
model named `sortFieldName`, e.g.:
```php
// return all posts sorted by creation date in descending order
Post::sorted('created_at,desc')->get();

// in model class overwrite the sorting logic so that 'created' field is used instead of 'created_at'
public function sortCreatedAt($query, $direction = 'desc')
{
    return $query->orderBy('created', $direction);
}
```

## Defining default sorting criteria

It is possible to define default sorting criteria that will be used if no sorting criteria are provided in the request or
passed to `sorted` method of your model. Default sorting criteria should be defined in $defaultSortCriteria property, e.g.:

```php
// sort by latest first
protected $defaultSortCriteria = 'created_at,desc';
```

## Additional configuration

 If you are using `sort` request parameter for other purpose, you can change the name of the parameter that will be
 interpreted as sorting criteria by setting a `$sortParameterName` property in your model, e.g.:
```php
 protected $sortParameterName = 'sortBy';
```
