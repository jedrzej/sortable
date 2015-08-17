# Sortable trait for Laravel's Eloquent models

This package adds sorting functionality to Eloquent models in Laravel 4/5.

You could also find those 2 packages useful:

- [Searchable](https://github.com/jedrzej/searchable) - Allows filtering your models using request parameters
- [Withable](https://github.com/jedrzej/withable) - Allows eager loading of relations using request parameters

## Composer install

Add the following line to `composer.json` file in your project:

    "jedrzej/sortable": "0.0.2"
	
or run the following in the commandline in your project's root folder:	

    composer require "jedrzej/sortable" "0.0.2"

## Setting up sortable models

In order to make an Eloquent model sortable, add the trait to the model and define a list of fields that the model can be sorted by.
You can either define a `$sortable` property or implement a `getSortableAttributes` method if you want to execute some logic to define
list of sortable fields.

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

In order to make all fields sortable put an asterisk `*` in the list of sortable fields:

    public $sortable = ['*'];

## Sorting models

`SortableTrait` adds a `sorted()` scope to the model - you can pass it a query being an array of sorting conditions:
 
    // return all posts sorted by creation date in descending order
    Post::sorted(['sort' => 'created_at,desc'])->get();
    
    // return all users sorted by level in ascending order and then by points indescending orders
    User::sorted(['sort' => ['level,asc', 'points,desc'])->get();
 
 or it will use `Input::all()` as default:
    
    // return all posts sorted by creation date in descending order by appending to URL
    ?sort=created_at,desc
    //and then calling
    Post::sorted()->get();

    // return all users sorted by level in ascending order and then by points indescending orders by appending to URL
    ?sort[]=level,asc&sort[]=points,desc
    // and then calling
    User::sorted()->get();