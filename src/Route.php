<?php
declare(strict_types=1);

namespace Minimal\Route;

/**
 * 路由类
 */
class Route extends Collection
{
    /**
     * 设置规则
     */
    public function setRule(string $rule) : static
    {
        return $this->set('rule', $rule);
    }

    /**
     * 获取规则
     */
    public function getRule() : ?string
    {
        return $this->has('rule') ? $this->get('rule') : null;
    }


    /**
     * 添加请求方式
     */
    public function addMethod(string $method) : static
    {
        return $this->addMethods([$method]);
    }

    /**
     * 添加多个请求方式
     */
    public function addMethods(array $methods) : static
    {
        return $this->append('methods', $methods, true);
    }

    /**
     * 是否存在请求方式
     */
    public function hasMethod(string $method) : bool
    {
        return $this->has('methods') && in_array(strtoupper($method), $this->get('methods'));
    }

    /**
     * 获取请求方式
     */
    public function getMethods() : array
    {
        return $this->has('methods') ? $this->get('methods') : [];
    }


    /**
     * 设置回调
     */
    public function setCallback(mixed $callback) : static
    {
        return $this->set('callback', $callback);
    }

    /**
     * 获取回调
     */
    public function getCallback() : mixed
    {
        return $this->has('callback') ? $this->get('callback') : null;
    }

    /**
     * 中间件
     */
    public function middleware(string ...$classes) : static
    {
        return $this->addMiddlewares($classes);
    }

    /**
     * 添加中间件
     */
    public function addMiddleware(array $class) : static
    {
        return $this->addMiddlewares([$class]);
    }

    /**
     * 添加多个中间件
     */
    public function addMiddlewares(array $classes) : static
    {
        return $this->append('middlewares', $classes);
    }

    /**
     * 是否存在中间件
     */
    public function hasMiddleware(string $class) : bool
    {
        return $this->has('middlewares') && in_array($class, $this->get('middlewares'));
    }

    /**
     * 获取中间件
     */
    public function getMiddlewares() : array
    {
        return $this->has('middlewares') ? $this->get('middlewares') : [];
    }



    /**
     * 获取数据
     */
    public function getData() : array
    {
        $data = $this->hasParent() ? $this->getParent()->getData() : [];

        foreach ($this->bindings as $key => $value) {
            if ($key == 'rule' && !empty($data[$key])) {
                $value = $data[$key] . '/' . $value;

                $value = ltrim($value, '/');
                $value = '/' . $value;
            }

            if ($key == 'callback') {
                $callback = $value ?? [null, $this->bindings['rule']];
                if (is_string($callback)) {
                    $callback = [null, $callback];
                }
                if (is_array($callback) && empty($callback[0])) {
                    $callback[0] = $data['controller'] ?? null;
                }
                $value = $callback;
            }

            if (!is_object($value)) {
                $data[$key] = $value;
            }
        }

        return $data;
    }
}