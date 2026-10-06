<?php

namespace App\Core;

class Router
{
    protected array $routes = [];
    protected array $namedRoutes = [];
    protected array $groupStack = [];

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function get(string $path, array|string|\Closure $handler, array $middleware = []): self
    {
        return $this->addRoute('GET', $path, $handler, $middleware);
    }

    public function post(string $path, array|string|\Closure $handler, array $middleware = []): self
    {
        return $this->addRoute('POST', $path, $handler, $middleware);
    }

    public function put(string $path, array|string|\Closure $handler, array $middleware = []): self
    {
        return $this->addRoute('PUT', $path, $handler, $middleware);
    }

    public function delete(string $path, array|string|\Closure $handler, array $middleware = []): self
    {
        return $this->addRoute('DELETE', $path, $handler, $middleware);
    }

    protected function addRoute(string $method, string $path, array|string|\Closure $handler, array $middleware = []): self
    {
        $prefix = '';
        $groupMiddleware = [];

        foreach ($this->groupStack as $group) {
            if (isset($group['prefix'])) {
                $prefix .= '/' . trim($group['prefix'], '/');
            }
            if (isset($group['middleware'])) {
                $groupMiddleware = array_merge($groupMiddleware, (array)$group['middleware']);
            }
        }

        $fullPath = '/' . trim($prefix . '/' . trim($path, '/'), '/');
        if ($fullPath === '') {
            $fullPath = '/';
        }

        $mergedMiddleware = array_merge($groupMiddleware, $middleware);

        // Convert {param} to regex named group
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $fullPath);
        $regex = '#^' . $pattern . '$#';

        $this->routes[] = [
            'method' => $method,
            'path' => $fullPath,
            'regex' => $regex,
            'handler' => $handler,
            'middleware' => $mergedMiddleware,
        ];

        return $this;
    }

    public function group(array $attributes, callable $callback): void
    {
        $this->groupStack[] = $attributes;
        $callback($this);
        array_pop($this->groupStack);
    }

    public function dispatch(Request $request): Response
    {
        // 0. Pelindung Global Anti-SQL Injection & WAF Shield
        $firewall = new \App\Middleware\Firewall();
        $firewallResponse = $firewall->handle($request);
        if ($firewallResponse instanceof Response) {
            return $firewallResponse;
        }

        $method = $request->getMethod();
        $path = $request->getPath();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['regex'], $path, $matches)) {
                $params = [];
                foreach ($matches as $key => $val) {
                    if (is_string($key)) {
                        $params[$key] = $val;
                    }
                }

                // Execute route middleware
                $middlewareResponse = $this->runMiddleware($route['middleware'], $request);
                if ($middlewareResponse instanceof Response) {
                    return $middlewareResponse;
                }

                return $this->executeHandler($route['handler'], $request, $params);
            }
        }

        // Route not found -> 404
        http_response_code(404);
        return View::render('errors.404', [], null)->setStatusCode(404);
    }

    protected function runMiddleware(array $middlewareList, Request $request): ?Response
    {
        $middlewareMap = [
            'firewall' => \App\Middleware\Firewall::class,
            'auth' => \App\Middleware\Auth::class,
            'guest' => \App\Middleware\Guest::class,
            'role' => \App\Middleware\Role::class,
            'csrf' => \App\Middleware\Csrf::class,
        ];

        foreach ($middlewareList as $item) {
            $name = $item;
            $param = null;

            if (str_contains($item, ':')) {
                [$name, $param] = explode(':', $item, 2);
            }

            if (isset($middlewareMap[$name])) {
                $class = $middlewareMap[$name];
                $instance = new $class();
                $result = $instance->handle($request, $param);
                if ($result instanceof Response) {
                    return $result;
                }
            }
        }

        return null;
    }

    protected function executeHandler(array|string|\Closure $handler, Request $request, array $params): Response
    {
        if ($handler instanceof \Closure) {
            $response = $handler($request, ...array_values($params));
        } elseif (is_string($handler) && str_contains($handler, '@')) {
            [$controllerName, $methodName] = explode('@', $handler, 2);
            $fullClass = "App\\Controllers\\{$controllerName}";

            if (!class_exists($fullClass)) {
                throw new \Exception("Controller class {$fullClass} tidak ditemukan.");
            }

            $controller = new $fullClass();
            if (!method_exists($controller, $methodName)) {
                throw new \Exception("Method {$methodName} tidak ditemukan pada {$fullClass}.");
            }

            $response = $controller->$methodName($request, ...array_values($params));
        } elseif (is_array($handler) && count($handler) === 2) {
            [$class, $method] = $handler;
            $controller = is_string($class) ? new $class() : $class;
            $response = $controller->$method($request, ...array_values($params));
        } else {
            throw new \Exception("Route handler tidak valid.");
        }

        if ($response instanceof Response) {
            return $response;
        }

        if (is_string($response)) {
            $resp = new Response();
            return $resp->html($response);
        }

        if (is_array($response)) {
            $resp = new Response();
            return $resp->json($response);
        }

        return new Response();
    }
}
