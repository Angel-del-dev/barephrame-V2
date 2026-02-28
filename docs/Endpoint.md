# Version
```php
#[Version(int n)]
```
The **Version** attribute is optional, when set will
prepend the version number to the endpoint.   
For example if the **Version** is set to 3:
```php
#[Version(3)]
```
That route will be prefixed by `/v3`

# Route
```php
#[Route('/')]
```
The **Route** attribute is not optional, without the
attribute, the controller wont be available/public.  
Routes need to be compiled/cached in order to be available.

## Dynamic endpoints
```php
#[Route('/users/{code}')]
```
Dynamic endpoints can be created by surrounding a portion 
of the endpoint in curly braces.  
By default all dynamic portions will be treated
as strings, but type can be specified to
change the behaviour.
```php
#[Route('/users/{code:string}')]
#[Route('/users/{code:integer}')]
```
## Middlewares
The **Route** attribute has an optional second parameter
which is used to validate the request before entering the
controller.  
There's several pre-built middlewares but custom ones
can also be created.
```php
#[Route('/users/{code:int}', JWTMiddleware::class, RoleMiddleware::class)]
```

# Methods
The allowed method is dependant on the extension of the file.  
```bash
*.get.php
*.post.php
*.put.php
*.delete.php
*.patch.php
*.options.php
```

# Parameters

One parameter is always given to the endpoint, which is the `Request` object and its always the last parameter.

## Request object
```php
use src\lib\request\Request;
```
The request object contains various bits of information, from `headers` to `parameters`.
It is not necessary to consume this object in an endpoint if its not needed.
```php
function index(Request $req); // This will work
function index(); // This will also work
```

## Dynamic parameters
```php

/*
    When routes contain dynamic parameters, they  
    will be passed to the endpoint function  
    in order with the same data type
*/
#[Route('/users/{code:int}')]
function index(int $code, Request $req);

#[Route('/users/{code:string}')]
function index(string $code, Request $req);
```

# Full example

```php
// /pages/users/[user].get.php
#[Version(1)]
#[Route('/users/{code:int}', JWTMiddleware::class, RoleMiddleware::class)]
function index() {
    
}
```