# Logs
Log files will be created in `/logs/$today.log`.

Unhandled exceptions will create an entry in the logs
specifying the file and line that contain the error.

## Usage
```php
use Barephrame\Core\Log\Log;

Log::store('Error', 'Error found in...');

/*
    The previous code will generate the following line in the logs file:
    H:i:s [Error] Error found in...
*/
```

## Helper functions
```php
Log::success('...');
Log::warning('...');
Log::error('...');
Log::information('...');
```