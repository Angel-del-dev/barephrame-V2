# Database connection
Barephrame supports connections for various
`database engines`.

List of supported engines:
+ Postgres
+ Mysql
+ Firebird

## Requirements
All `database` connections are handled via `PDO`.

+ **Package install**: Depending on the system the application
is being `developed/deployed`, some packages need to be installed
    * In linux, it is required to install `php-pgsql` and `php8.5-pgsql` to
    connect to `Postgres` databases
+ **Activate plugins**: In the `php.ini` file, it is required to
uncomment the required extensions for the required database including
`PDO` extensions

## Things to consider
Barephrame allows opening multiple connections at the
same time.

When using multiple connections, they don't need
to be using the same engine.

For example:
+ main connection: Postgres
+ secondary connection: Mysql
+ third connection: Firebird

## Usage

### Connection

The `DatabaseTypes` enum must be used to handle connections
and store them correctly, as each engine uses different connection
`dsn`(Connection strings)
```php
use Barephrame\Core\Database\DatabaseTypes;

// List of cases
enum DatabaseTypes {
    case MYSQL;
    case FIREBIRD;
    case POSTGRES;
}
```

The connection class can be found in:
```php
use Barephrame\Core\Database\Connections;
```

If the `Database` section in the `app.ini` is specified:
```ini
[Database]
HOST = 
USER = 
PASSWORD = 
PORT = 
NAME = 
```

The `Connections::mainConnection` function can be used
to automatically connect.
```php
$connection = Connections::mainConnection(DatabaseTypes::POSTGRES);
```

## Connection to a database manually
the `Connections::connect` function can be used to connect
manually to a database. For example to use as a
secondary connection or feching credentials from another
source.

```php
$connection = Connections::connect($keyname, $host, $name, $user, $password, $port, DatabaseTypes::POSTGRES);
```

## Useful functions
There are various functions to help development.

If its required to use a connection already open,
the `use` function can be used to return the connection
from the name it has been given during the initial connection
without having to pass the conection to every called method.
> When using the `mainConnection` function, it will
automatically be given the keyname `main`

```php
$connection = Connections::use($keyname);
```

To check if a connection has been established previously, the
`isConnected` can be used.

```php
$isConnected = Connections::isConnected($keyname);
```

# Executing queries

The `statement` function prepares sql statements with
a query string as a parameter.

When calling the `statement` function a `DatabaseResult`
instance will be returned.
```php
use Barephrame\Core\Database\DatabaseResult;
```
Which can be used to get the result of a query or
execute a statement using the `execute` function.
```php
$connection = Connections::mainConnection(DatabaseTypes::POSTGRES);

$sql = $connection->statement('SELECT * FROM TABLENAME');
$result = $sql->execute();
$sql->close();
```
The `execute` function returns an `array` with all the
requested rows.

# Parameters in queries

The `parameters` property can be used in the `DatabaseResult`
instance to specify values.

```php
$sql = $connection->statement('SELECT * FROM TABLENAME WHERE ID > :ID');
$sql->parameters->ID = 1000;
$result = $sql->execute();
$sql->close();
```

# Closing queries

After the result has ben obtained, the `close` method
may be used to free resources.