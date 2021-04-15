<?php
declare(strict_types=1);

namespace Minimal\Route;

/**
 * 数据集合类
 */
class Collection
{
    /**
     * 数据绑定
     */
    protected array $bindings = [];

    /**
     * 设置数据
     */
    public function set(string $key, mixed $value) : static
    {
        $this->bindings[$key] = $value;

        ksort($this->bindings);

        return $this;
    }

    /**
     * 追加数据
     */
    public function append(string $key, array $values, bool $upper = false, bool $unique = true) : static
    {
        $data = $this->has($key) ? $this->get($key) : [];

        if ($upper) {
            $values = array_map(fn($v) => strtoupper($v), $values);
        }

        if ($unique) {
            $values = array_filter($values, fn($v) => !in_array($v, $data));
        }

        return $this->set($key, array_merge($data, $values));
    }

    /**
     * 是否存在数据
     */
    public function has(string $key) : bool
    {
        return isset($this->bindings[$key]);
    }

    /**
     * 获取数据
     */
    public function get(string $key) : mixed
    {
        return $this->bindings[$key];
    }

    /**
     * 获取所有数据
     */
    public function all() : array
    {
        return $this->bindings;
    }



    /**
     * 设置上级
     */
    public function setParent(Collection $parent) : static
    {
        return $this->set('parent', $parent);
    }

    /**
     * 是否存在上级
     */
    public function hasParent() : bool
    {
        return $this->has('parent');
    }

    /**
     * 获取上级
     */
    public function getParent() : ?Collection
    {
        return $this->hasParent() ? $this->get('parent') : null;
    }


    /**
     * 获取数据
     */
    public function getData() : array
    {
        $parentData = $this->hasParent() ? $this->getParent()->getData() : [];

        $data = array_merge($parentData, $this->bindings);

        $data = array_filter($data, fn($item) => !is_object($item));

        return $data;
    }
}