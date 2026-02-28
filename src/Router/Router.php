<?php

namespace src\Router;

use Exception;
use src\lib\request\Request;

class Router {
    private string $root;
    private Request $request;
    private array $cache_routes;
    public function __construct() {
        $this->root = $_SERVER['DOCUMENT_ROOT'].'..';

        $this->request = new Request();
        $this->parse_cache_routes();
    }
    public function redirect():void {
        $endpoint = $this->sanitize_endpoint();
        // TODO Choose the right endpoint path from route cache tree
        print_r('<pre>');
        print_r($endpoint);
        exit;        
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