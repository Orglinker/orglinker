Library for orglinker API. To install an instance of orglinker, go to orglinker.com

Install library ```composer require orglinker/external-integration```

First you need to specify the connection parameters.

```php
use \Orglinker\Orglinker;


$config = [
    'email' => 'test@orglinker.com',
    'password' => 'adminpassword',
    'domain_name' => 'your.domain.orglinker.com'
];
$orglinker = new Orglinker($config);
```

- GET request
```php
$data = [
    'table' => 'contact', //required field
    'page' => '1', //optional field
    'per-page' => '25', //optional field
    'conditions' => '[{"comparator":"=","field":"id","value":"3"}]', //optional field
];
echo $orglinker->getItems($data);
```
Available Comparators: ```=, >=, <=, like, <, >```
- POST request
```php
$data = [
    'table' => 'contact', //required field
    'objects' => '[{"name_loc":[{"ru":"contact1","en":" contact1"}], "short_name":" contact1"},{"name_loc":[{"ru":" contact2","en":" contact2"}],"short_name":"contact2"}]', //required field
];
echo $orglinker->createItems($data);
```
- PUT request

When updating, be sure to pass id in each object!
```php
$data = [
    'table' => 'contact', //required field
    'objects' => '[{"name_loc":[{"ru":"contact1","en":" contact1"}], "short_name":" contact1", "id":"12"},{"name_loc":[{"ru":" contact2","en":" contact2"}],"short_name":"contact2", "id":"13"}]', //required field
];
echo $orglinker->updateItems($data);
```
- DELETE request
```php
$data = [
    'table' => 'contact', //required field
    'ids' => '12,13' //required field
];
echo $orglinker->deleteItems($data);
```

Features of working with trees
1. When a POST request is made, pass the parent_id field to objects
```[{"name_loc":[{"ru":"treename","en":" treename "}], "parent_id":"25"}]```

2. When moving (PUT request), be sure to pass the move = 1 parameter. In the object, pass the id and parent_id (where we move). Example:
```php
$data = [
    'table' => 'contract', //required field
    'objects' => '[{"parent_id":"1","id":"25"},{"parent_id":"1","id":"24"}]', //required field
    'move' => '1', //required field
];
echo $orglinker->updateItems($data);
```
