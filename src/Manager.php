<?php
declare(strict_types=1);

namespace Minimal\Route;

use Closure;

/**
 * 路由管理类
 */
class Manager
{
    /**
     * 当前路由
     */
    protected Collection $current;

    /**
     * 路由列表
     */
    public array $routes = [];

    /**
     * 缓存的路由列表
     */
    protected array $cacheRoutes = [];

    /**
     * 缓存路由
     */
    public function cache(array $data = null) : array
    {
        if (!is_null($data)) {
            return $this->cacheRoutes = $data;
        }

        if (!empty($this->routes) && empty($this->cacheRoutes)) {
            foreach ($this->routes as $route) {
                $this->cacheRoutes[] = $route->getData();
            }
        }

        return $this->cacheRoutes;
    }


    /**
     * 匹配路由
     */
    public function dispatch(string $host, string $method, string $rule) : array
    {
        $routes = $this->cache();

        foreach ($routes as $route) {
            $checkDomain = empty($route['domains']) || (isset($route['domains']) && in_array($host, $route['domains']));
            $checkMethod = empty($route['methods']) || (isset($route['methods']) && in_array($method, $route['methods']));
            $checkRule = empty($route['rule']) || $rule === $route['rule'];
            if ($checkDomain && $checkMethod && $checkRule) {
                return $route;
            }
        }

        return [];
    }


    /**
     * 按域名分组
     */
    public function domain(string|array $hosts, Closure $callback) : Collection
    {
        return $this->group('', '', $callback)->addDomains(is_array($hosts) ? $hosts : [$hosts]);
    }

    /**
     * 按前缀分组
     */
    public function group(string $rule, string $class, Closure $callback) : Collection
    {
        $group = new Group();
        $group->setRule($rule);
        $group->setController($class);

        if (!isset($this->current)) {
            $this->current = new Group();
        }
        $group->setParent($origin = $this->current);

        $this->current = $group;
        $callback();
        $this->current = $origin;

        return $group;
    }

    /**
     * 匹配
     */
    public function match(array $methods, string $rule, mixed $callable = null) : Collection
    {
        $route = new Route();
        $route->setRule($rule);
        $route->addMethods($methods);
        $route->setCallback($callable);

        if (!isset($this->current)) {
            $this->current = new Group();
        }
        $route->setParent($this->current);

        $this->routes[] = $route;

        return $route;
    }

    /**
     * ANY
     */
    public function any(string $rule, mixed $callable = null) : Collection
    {
        return $this->match([], $rule, $callable);
    }

    /**
     * GET
     */
    public function get(string $rule, mixed $callable = null) : Collection
    {
        return $this->match(['GET'], $rule, $callable);
    }

    /**
     * POST
     */
    public function post(string $rule, mixed $callable = null) : Collection
    {
        return $this->match(['POST'], $rule, $callable);
    }

    /**
     * PUT
     */
    public function put(string $rule, mixed $callable = null) : Collection
    {
        return $this->match(['PUT'], $rule, $callable);
    }

    /**
     * PATCH
     */
    public function patch(string $rule, mixed $callable = null) : Collection
    {
        return $this->match(['PATCH'], $rule, $callable);
    }

    /**
     * DELETE
     */
    public function delete(string $rule, mixed $callable = null) : Collection
    {
        return $this->match(['DELETE'], $rule, $callable);
    }

    /**
     * OPTIONS
     */
    public function options(string $rule, mixed $callable = null) : Collection
    {
        return $this->match(['OPTIONS'], $rule, $callable);
    }
}