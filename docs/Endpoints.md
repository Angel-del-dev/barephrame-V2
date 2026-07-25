# Attributes
Barephrame uses `php Attributes` to specify
routing information.

The routing information is relevant in the
[compile-routes](./CLI.md) step.

## Version
```php
#[Version(int n)]
```
The **Version** attribute is optional, when set will
prepend the version number to the endpoint.   
For example if the **Version** is set to 3:
```php
#[Version(3)]
```
The endpoint will be prefixed by `/v3`

## Route
```php
#[Route('/')]
```
The **Route** attribute is not optional, without the
attribute, the endpoint wont be discovered in
the [compile-routes](./CLI.md) step.

Routes need to be `compiled` in order to be available.

### Dynamic endpoints
```php
#[Route('/users/:code')]
```
Dynamic endpoints can be created by adding the
character `:` before the name of the parameter.  

`Note:` The name of the parameter is irrelevant,
parameters will be passed as arguments in order of discovery. 
```php
#[Route('/users/:code')]
```
### Middlewares
The **Route** attribute has an optional second parameter
which is used to validate the request before entering the
controller.  

`Note:` The controller will only be reached as long
as every specified middleware returns a response type `200`
```php
#[Route('/users/:code', JWTMiddleware::class, RoleMiddleware::class)]
```

## Methods
By default, every endpoint is treated as `GET`.

It can be changed using the `Method` Attribute
```bash
#[Method('POST')]
#[Method('PUT')]
#[Method('PATCH')]
...
```

## Parameters

One parameter is always given to 
the endpoint, which is 
the `Request` object and its 
always the first parameter.

### Request object
```php
use Barephrame\Core\Request\Request;
```
The request object contains various bits of information, from `headers` to `parameters`.
It is not necessary to consume this object in an endpoint if its not needed.
```php
function index(Request $req); // This will work
function index(); // This will also work
```

### Dynamic parameters
```php

/*
    When routes contain dynamic parameters, they  
    will be passed to the endpoint function  
    in order with the same data type
*/
#[Route('/users/:code')]
public function index(Request $req, int $code);

#[Route('/users/:code')]
public function index(Request $req, string $code);
```

## Full example

```php
// /App/users/User.php
<?php

namespace App\users;

use Barephrame\Attributes\Method;
use Barephrame\Attributes\Route;
use Barephrame\Core\Request\Request;

class Users {
    #[Route('/users')]
    public function ListAllUsers() 
    {}

    #[Method('POST')]
    #[Route('/users')]
    public function CreateUser() 
    {}

    #[Method('GET')]
    #[Route('/users/:code')]
    public function GetUserData(Request $request, string $code) 
    {}
}
```