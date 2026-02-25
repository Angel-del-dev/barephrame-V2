<?php

if(php_sapi_name() !== 'cli') {
    exit("Only CLI\n");
}

// Configuration

$base_pages_path = realpath(__DIR__.'/../../pages');

// End of configuration

if(is_file(__DIR__.'/routes.cache.php')) {
    echo "Purging previous route cache\n";
    unlink(__DIR__.'/routes.cache.php');
}

require_once(__DIR__.'/../autoload.php');

use src\Attributes\Route;
use src\Attributes\Version;

echo "Finding files in: '{$base_pages_path}'\n";

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($base_pages_path)
);

$cached_routes = [];
foreach($iterator as $file) {
    if(!$file->isFile() || $file->getExtension() !== 'php') {
        continue;
    }

    $file_name = $file->getFileName();
    $parts = explode('.', $file_name);

    $http_method = strtoupper($parts[count($parts) - 2] ?? 'GET');
    $defined_functions_before = get_defined_functions()['user'];

    $real_path = $file->getRealPath();
    echo "Caching {$http_method} '{$real_path}'\n";
    require_once($real_path);
    
    $defined_functions_after = get_defined_functions()['user'];

    $new_functions_only = array_diff($defined_functions_after, $defined_functions_before);
    
    foreach($new_functions_only as $function) {
        $reflection = new ReflectionFunction($function);
        $route_attributes = $reflection->getAttributes(Route::class);
        $version_attributes = $reflection->getAttributes(Version::class);

        $count_route_attributes = count($route_attributes);
        $count_version_attributes = count($version_attributes);
        for($i = 0 ; $i < $count_route_attributes ; $i++) {
            $attribute = $route_attributes[$i];
            $instance = $attribute->newInstance();
            $path = explode('/', $instance->path);
            array_shift($path);
            
            if($count_version_attributes > 0 && $i < $count_version_attributes) {
                $version_instance = $version_attributes[$i]->newInstance();
                array_unshift($path, sprintf("v%d", $version_instance->version));
            }

            $cached_pointer = &$cached_routes;
            $jump_count = count($path) - 1;
            for($jump_counter = 0 ; $jump_counter <= $jump_count ; $jump_counter++) {
                $jump = trim($path[$jump_counter]);
                if(strlen($jump) > 0 && $jump[0] === '{') {
                    // Dynamic parameter
                    $format_jump = str_replace(['{', '}'], '', $jump);
                    $format_jump = explode(':', $format_jump);
                    $count_format_jump = count($format_jump);
                    $parameter_type = '';
                    if($count_format_jump >= 2) {
                        $parameter_type = strtoupper($format_jump[1]);
                    }
                    if(trim($parameter_type) === '') $parameter_type = 'STRING';

                    $cached_pointer['__PARAMS__'] = [
                        'type' => $parameter_type,
                        'name' => $format_jump[0]
                    ];
                    $jump = '__PARAMS__';                    
                }
                if(!isset($cached_pointer[$jump])) $cached_pointer[$jump] = [];
                $cached_pointer = &$cached_pointer[$jump];
                if($jump_counter < $jump_count) continue;
                if(!isset($cached_pointer['__ROUTE__'])) $cached_pointer['__ROUTE__'] = [];
                $cached_pointer['__ROUTE__'][$http_method] = [
                    'path' => $instance->path,
                    'action' => $function,
                    'middlewares' => $instance->middlewares
                ];
            }
        }
    }
}

$export = var_export($cached_routes, true);

$content = "<?php\n\nreturn " . $export . ";\n";

file_put_contents(__DIR__ . '/routes.cache.php', $content);