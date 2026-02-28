<?php

namespace src\Router;

use Exception;
use src\lib\request\Request;
use src\lib\response\Response;
use src\lib\response\NotFound;
use src\lib\response\MethodNotAllowed;

class Router {
    private string $root;
    private Request $request;
    private array $cache_routes;
    public function __construct() {
        $this->root = $_SERVER['DOCUMENT_ROOT'].'..';

        $this->request = new Request();
        $this->parse_cache_routes();
    }
    public function redirect():Response {
        $endpoint = $this->sanitize_endpoint();
        $selected_route = &$this->cache_routes;
        $parameters = [];

        foreach($endpoint as $jump) {
            if(!isset($selected_route[$jump])) {                
                if(!isset($selected_route['__PARAMS__'])) {
                    return new NotFound()->send();
                }
                // Dynamic parameters            
                $selected_route = &$selected_route['__PARAMS__'];
                $parameters[] = $this->format_parameter($selected_route['type'], $jump);
                continue;
            }
            $selected_route = &$selected_route[$jump];
        }
        $http_method = strtoupper($_SERVER['REQUEST_METHOD']);

        if(!isset($selected_route['__ROUTE__'])) {
            return new NotFound()->send();
        }
        $methods = $selected_route['__ROUTE__'];
        if(!isset($methods[$http_method])) {
            return new MethodNotAllowed()->send();
        }
        $configuration = $methods[$http_method];
        if (!function_exists($configuration['action'])) {
            $file = $this->root . '/' . str_replace('\\', '/', $configuration['action']) .'.'. strtolower($http_method).'.php';
            if(!file_exists($file)) {
                throw new Exception("Cannot load file '{$file}'");
            }
            require_once($file);
        }

        $parameters[] = $this->request;
        $response = call_user_func('\\'.$configuration['action'], ...$parameters);
        if(!$response instanceof Response) {
            if(is_null($response)) {
                $response = new Response();
            } else {   
                $response = new Response()
                    ->set_data($response);
            }
        }
        return $response;
    }

    private function format_parameter(string $type, string $value):int|string {
        $cloned_value = $value;
        switch(strtolower($type)) {
            case 'integer':
            case 'int':
                $cloned_value = intval($value);
            break;
            default:
                // Don't format strings
            break;
        }
        return $cloned_value;
    }

    private function parse_cache_routes():void {
        $cache_routes_file = "{$this->root}/src/Cache/routes.cache.php";
        if(!is_file($cache_routes_file)) {
            throw new Exception("Routes are not cached.\nCache them using:\nsrc/Cli/exec.php --cache-routes");
        }
        $this->cache_routes = require_once($cache_routes_file);
    }

    private function sanitize_endpoint():array {
        $endpoint = explode('/', $this->request->endpoint);
        array_shift($endpoint);
        return $endpoint;
    }
}