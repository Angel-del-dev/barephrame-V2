# Middlewares

The `IMiddleware` interface must be used to propertly define middlewares.

```php
use Barephrame\Core\Contracts\Middleware\IMiddleware;
```

## Definition

Exactly one argument is given to the middleware, the `Request` object
required to validate requests.

A `Response` object must be returned. It can either be a manually
created `Response` object or an abstraction such as `OK::send`,
`BadRequest::send`, ...

The request will only go to the next middleware or to the controller
if the middleware returns a `Response` with `200` as the status code. It will
otherwise be bloqued and the actual response the client will get is what the
middleware returns. 

```php
class JWTMiddleware implements IMiddleware {
    public function validate(Request $request):Response 
    {
        // Insert logic here
        return OK::send();
    }
}
```

## Usage
The `middleware` is used in the `Route` attribute of a controller.
```php
#[Route('/users', [JWTMiddleware::class])]
```

**`NOTE`** After modifying the middlewares used in an endpoint, the routes must be
recompiled using the [CLI](./CLI.md) tools 