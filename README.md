Ontraport API
==============

<p align="">
<a href="https://travis-ci.org/wallstreetio/ontraport"><img src="https://img.shields.io/travis/wallstreetio/ontraport/master.svg?style=flat-square" alt="Build Status"></img></a>
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square" alt="Software License"></img></a>
<a href="https://github.com/wallstreetio/ontraport/releases"><img src="https://img.shields.io/github/release/wallstreetio/ontraport.svg?style=flat-square" alt="Latest Version"></img></a>
</p>

The Ontraport API package, by [WallStreet.io](https://wallstreet.io), is a simple and elegant way to communicate with [Ontraport](https://ontraport.com).

```php
$products = $ontraport->products
    ->where('name', 'ONTRAPages')
    ->orWhere('name', 'ONTRAForms')
    ->get();
```

## Installation

```bash
composer require wallstreetio/ontraport
```

## Configuration

Once you have the package installed, you can starting using the API right away.

```php
$ontraport = new \Wsio\Ontraport\Ontraport('APP_ID', 'APP_KEY');
```

## Usage

The Ontraport API provides a fluent, query builder syntax that you might be used to from the popular `Eloquent` ORM.

In the examples below we will focus on the Contacts endpoint in Ontraport, but the same syntax can apply to any of the [Ontraport objects](#list-of-provided-ontraport-objects).

#### List Objects

The `get` method will return an array of all of the objects (within Ontraport's limit of 50).

```php
$contacts = $ontraport->contacts->get();
```

You may wish to add constraints to the query to retrieve a subset of the objects. For example:

```php
$contacts = $ontraport->contacts
    ->where('firstname', 'Bob')
    ->orderBy('id')
    ->get();
```

In the above example, we fetched all the contacts with the firstname, Bob, in ascending order sorted by the `id` field.

For pagination, you can limit the number of results to retrieve as well as the offset to start.

```php
$contacts = $ontraport->contacts
    ->range(25)
    ->start(50)
    ->get();
```

The above examples will grab 25 contacts offset by the first 50 contacts.

#### Find Object

In addition to retrieving all of the objects, you may also retrieve single objects using `find` and `first`. Instead of an array of objects, these methods return a single object:

```php
$contact = $ontraport->contacts->find(1);
```

Like the `get` method, we can stack the `first` method with constraints as well.

```php
$contact = $ontraport->contacts->where('email', 'dev@wallstreet.io')->first();
```

The `contact` variable that is returned is an instance of `\Wsio\Ontraport\Fluent` which allows us to treat the Ontraport response as an object.

```php
echo $contact->email; // dev@wallstreet.io
```

#### Create Object

```php
$contact = $ontraport->contacts->create([
    'email' => 'dev@wallstreet.io'
]);
```

You may also come across situations where you want to update an existing record or create a new record if none exists.
The Ontraport API provides a `saveOrUpdate` method to allow for this.

```php
$contact = $ontraport->contacts->saveOrUpdate([
    'firstname' => 'Tamer',
    'email' => 'dev@wallstreet.io'
]);
```

#### Update Object

```php
$ontraport->contacts->update(1, [
    'email' => 'tamer@wallstreet.io'
]);

```

If we have already retrieved an object, we can change the attributes of the object using the `Fluent` interface.
When we are ready to save the changes we can call the `save` method. For example:

```php
$contact = $ontraport->contacts->find(1);

$contact->lastname = 'Rules';

$contact->save();
```

#### Delete Object

```php
$ontraport->contacts->delete(1);
```

Similary, if we have previously retrieved the object, we can utilize the `delete` method to remove the object in Ontraport.

```php
$contact = $ontraport->contacts->find(1);

$contact->delete();
```

#### Delete Many Objects

```php
$ontraport->contacts->delete() // uh-oh :)

$ontraport->contacts->where('email', 'tamer@wallstreet.io')->delete();
```

## Examples

Fetch all contacts where the lastname attribute is empty.

```php
$ontraport->contacts->where('lastname', null)->get();

// OR

$ontraport->contacts->whereNull('lastname')->get();
```

Fetch all the products with the names: ONTRAPages and ONTRAForms.

```php
$ontraport->products->whereIn('name', ['ONTRAPages', 'ONTRAForms'])->get();
```

Fetch all contacts in descending order.

```php
$ontraport->contacts->orderBy('id', 'desc')->get();

$ontraport->contacts->orderByDesc('id')->get();
```

The Ontraport API allows you to stack these methods on top of each other, for example:

```php
$ontraport->contacts
    ->whereNull('firstame')
    ->where('email', 'dev@wallstreet.io')
    ->orderByDesc('id')
    ->get();
```

In the above example, we grabbed all the `active` contacts without a `firstname` attribute in descending order.

## List of Provided Ontraport Objects

For the full list of objects that Ontraport provides and better documentation for each of the endpoints, please refer to the [Ontraport Documentation](http://api.ontraport.com/doc/#/).

* Contacts
* Tasks
* Staff
* Sequences
* Rules
* Messages
* Subscribers
* Notes
* Blasts
* Tags
* Products
* Purchases
* Fulfillments
* LandingPages
* CustomObjects

Each of these objects can be called directly on the Ontraport instance.

```php
$sequences = $ontraport->sequences->get();

$purchase = $ontraport->purchases->find(1);
```

## Extension

In attempt to mimic Ontraport's dynamic nature, internally all Ontraport endpoints are treated as if they were the same. If an endpoint does not exist or requires a different structure, extending/overriding is a breeze.

Let's say we have a `tasks` objects but it doesn't have the `assign` endpoint we need. We can extend the `ontraport` instance to allow for it.

First we will create a new `Task` class that extends the `Resource` class.

```php
class Task extends \Wsio\Ontraport\Resources\Resource
{
    protected $namespace = 'Task';

    public function assign(array $data = [])
    {
        return $this->ontraport->post('task/assign', $data);
    }
}
```

Then we call the extend method on an our `ontraport` instance.

```php
$ontraport->extend('tasks', Task::class);
```

The endpoint can now be accessed through the `ontraport` instance and `tasks` object.

```php
$ontraport->tasks->assign([
    'message_id' => 1,
    'due_date' => 'now'
]);
```

Likewise, this approach can be used for objects that this package does not offer in the list [above](#list-of-provided-ontraport-objects).

In most cases this can be a little much especially for one-off requests. The Ontraport instance provides `get`, `post`, `put`, and `delete` helper methods that you can utilize instead.

```php
$task = $ontraport->post('task/assign', [
    'message_id' => 1,
    'due_date' => 'now'
]);
```

# Documentation

* [Ontraport Documentation](http://api.ontraport.com/doc/#/)

# Tests

To run the testsuite, you will need to clone this repository and install the dev requirements.

```bash
git clone https://github.com/wallstreetio/ontraport.git ontraport
cd ontraport
composer install --dev
```
Then you can run the unit tests

```bash
phpunit
```

If you want to run the full test suite, you will need to edit your `phpunit.xml` file with the proper `ONTRAPORT_APP_ID` and `ONTRAPORT_API_KEY`.

> *Note: The full testsuite will run tests that will add, change, and delete contacts.* **Make sure you are using a testing account.**

# License

WallStreet.io Ontraport API is open-sourced software licensed under [The MIT License (MIT)](LICENSE).
