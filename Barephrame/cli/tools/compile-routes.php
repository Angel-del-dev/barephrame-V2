<?php

require_once(__DIR__.'/../../autoload.php');

use Barephrame\Attributes\Route;
use Barephrame\Attributes\Version;
use Barephrame\Attributes\Method;

function CompileRoutes():void {
    $rootCache = __DIR__.'/../../cache';
    $rootEndpoints = __DIR__.'/../../../App';

    if(!is_dir($rootCache)) {
        mkdir($rootCache);
    }

    if(is_file($rootCache.'/routes.php')) {
        echo "Purging previous compiled routes\n";
        unlink($rootCache.'/routes.php');
    }

    echo "Finding files in: $rootEndpoints\n";
    $fileIterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootEndpoints)
    ); 

    $compiledRoutes = [];

    foreach($fileIterator as $file) {
        if(!$file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }
        
        $fileRealPath = $file->getRealPath();

        $definedClassesBefore = get_declared_classes();
        echo "Compiling routes in '$fileRealPath'\n";
        require_once($fileRealPath);

        $definedClassesAfter = get_declared_classes();

        $newDefinedClasses = array_diff($definedClassesAfter, $definedClassesBefore);
 
        foreach($newDefinedClasses as $className) {
            $reflectionClass = new ReflectionClass($className);
            foreach($reflectionClass->getMethods() as $method) {
                $attributeMethod = $method->getAttributes(Method::class);
                $attributeVersion = $method->getAttributes(Version::class);
                $attributeRoute = $method->getAttributes(Route::class);
                if(count($attributeMethod) === 0) continue;

                $httpMethod = $attributeMethod[0]->newInstance()->method ?? 'GET';
                $version = null;
                if(count($attributeVersion) === 1) {
                    $version = $attributeVersion[0]->newInstance()->version;
                }

                $routeInstance = $attributeRoute[0]->newInstance();

                $route = $routeInstance->path;
                $middlewares = $routeInstance->middlewares;                
                $path = explode('/', $route);
                if(count($path) === 0) continue;
                if(trim($path[0]) === '') {
                    array_shift($path);
                }
                
                if(!is_null($version)) {
                    array_unshift($path, sprintf('v%d', $version));
                }

                $routePointer = &$compiledRoutes;
                $jumpCount = count($path) - 1;
                for($jumpCounter = 0 ; $jumpCounter <= $jumpCount; $jumpCounter++) {
                    $jump = trim($path[$jumpCounter]);

                    // Handle dynamic parameters
                    if(strlen($jump) > 0 && $jump[0] === ':') {
                        $formatJump = str_replace(':', '', $jump)
                            |> trim(...);
                        
                        $cachedPointer['__PARAMS__'] = [
                            'name' => $formatJump
                        ];
                        $jump = '__PARAMS__';
                    }
                    if(!isset($routePointer[$jump])) $routePointer[$jump] = [];
                    $routePointer = &$routePointer[$jump];
                    if($jumpCounter < $jumpCount) continue;
                    if(!isset($routePointer['__ROUTE__'])) $routePointer['__ROUTE__'] = [];
                    $routePointer['__ROUTE__'][$httpMethod] = [
                        'path' => $route,
                        'class' => $method->class,
                        'method' => $method->name,
                        'middleware' => $middlewares
                    ];
                }
            }
        }

    }

    $export = var_export($compiledRoutes, true);

    $content = "<?php\n\nreturn ".$export. ";\n";
    $content = $content
        |> (fn($compiledRoutesString) => preg_replace('/\s+/', ' ', $compiledRoutesString))
        |> (fn(string $compiledRoutesString) => preg_replace('/\s*,\s*/', ', ', $compiledRoutesString));
    file_put_contents($rootCache.'/routes.php', $content);
}