# Valid Responses

To avoid data contamination, the kernel will always check the `return type` produced from the endpoint and
format it correctly.

Even though `primitive types` and `complex types` are allowed an supported, it is encouraged to use the `Response` type shown at the end of this file.

## Primitive types
When an endpoint returns a primitive type such as:
* int
* boolean
* string
* float  

The kernel will automatically add a `200` `statuscode` and an `OK` message with the raw data `as is returned`
```php
#[Route('/')]
function index(Request $req):string {
    return "Hello world!";
}
```

## Complex types
Types such as:
* arrays
* objects
* classes  

Will automatically be converted as `json`, `200` `statuscode` and an `OK` message
```php
#[Route('/')]
function index(Request $req):array {
    return ['message' => 'Hello world!'];
}
```

## No return
In the case of not returning anything, the kernel will automatically add a `200` `statuscode` and an `OK` message but will **not** send any data back
```php
#[Route('/')]
function index(Request $req):void {
    echo "Hello world!";
}
```

## Response type

```php
use src\lib\response\Response;
```
The `Response` type is a class that allows for response customization.
```php
public function status(int $status, string $message):Response;

public function set_data(mixed $data):Response;

public function set_content_type(string $content_type):Response;

// Rate limiting
/* 
    This function will create a 'Retry-After'  
    header useful for rate limiting control
*/
public function set_retry_after(int $seconds):Response
``` 

## Pre-Built response types
There's several built-in `response` types for the most used options.
```php
use src\lib\response\BadRequest;
use src\lib\response\Forbidden;
use src\lib\response\MethodNotAllowed;
use src\lib\response\ContentTooLarge;
use src\lib\response\InternalServerError;
use src\lib\response\NotAcceptable;
use src\lib\response\NotFound;
use src\lib\response\RateLimitExeeded;
use src\lib\response\Unauthorized;
use src\lib\response\UnsuportedMediaType;
```