<?php

namespace Barephrame\Core\Router;

use Barephrame\Core\Request\Request;
use Barephrame\Core\Response\Common\BadRequest;
use Barephrame\Core\Response\Common\MethodNotAllowed;
use Barephrame\Core\Response\Common\NotFound;
use Barephrame\Core\Response\Response;
use Exception;
use NoDiscard;

class Router {
    private string $root;
    private Request $request;
    private array $compiledRoutes;

    public function __construct()
    {
        $this->root = $_SERVER['DOCUMENT_ROOT'].'..';
        $this->request = new Request();
        $this->parseCompiledRoutes();
    }

    #[NoDiscard]
    public function redirect():Response {
        $endpoint = $this->sanitizeEndpoint();
        $httpMethod = $this->request->method;

        $selectedRoute = &$this->compiledRoutes;
        $parameters = [];

        foreach($endpoint as $jump) {
            if(trim($jump) === '') {
                return BadRequest::send();
            }
            // If the endpoint chunk doesn't exist, it might mean there's a 
            // dynamic parameter
            if(!isset($selectedRoute[$jump])) {
                // The endpoint chunk does not exist and is not a dynamic parameter
                if(!isset($selectedRoute['__PARAMS__'])) {
                    return NotFound::send();
                }
                // The endpoint chunk does not exist and is a dynamic parameter
                $selectedRoute = &$selectedRoute['__PARAMS__'];
                $parameters[] = $jump;
                continue;
            }
            $selectedRoute = &$selectedRoute[$jump];
        }

        if(!isset($selectedRoute['__ROUTE__'])) {
            return NotFound::send();
        }

        $methods = $selectedRoute['__ROUTE__'];

        if(!isset($methods[$httpMethod])) {
            return MethodNotAllowed::send();
        }

        $configuration = $methods[$httpMethod];

        if(!class_exists($configuration['class'])) {
            throw new Exception("Cannot load class: '{$configuration['class']}'");
        }

        $classInstance = new $configuration['class'];

        if(!method_exists($classInstance, $configuration['method'])) {
            throw new Exception("Cannot load method: '{$configuration['class']}::{$configuration['method']}()'");
        }

        foreach($configuration['middleware'] as $middleware) {
            $response = new $middleware()->handle($this->request);
            if($response->status !== 200) {
                return $response;
            }
        }

        $response = $classInstance->{$configuration['method']}($this->request, ...$parameters);

        if(!$response instanceof Response) {
            if(is_null($response)) {
                $response = new Response()->setData($response);
            } else {
                $response = new Response();
            }
        }

        return $response;
    }

    private function parseCompiledRoutes():void {
        $filePath = "{$this->root}/Barephrame/cache/routes.php";
        if(!is_file($filePath)) {
            throw new Exception("Routes are not compiled");
        }

        $this->compiledRoutes = require_once($filePath);
    }

    private function sanitizeEndpoint():array {
        $endpointArray = explode('/', $this->request->endpoint);
        array_shift($endpointArray);
        return $endpointArray;
    }
}