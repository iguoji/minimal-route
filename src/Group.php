<?php
declare(strict_types=1);

namespace Minimal\Route;

/**
 * 路由分组类
 */
class Group extends Route
{
    /**
     * 设置控制器
     */
    public function setController(string $class) : static
    {
        return $this->set('controller', $class);
    }

    /**
     * 是否存在控制器
     */
    public function hasController() : bool
    {
        return $this->has('controller');
    }

    /**
     * 获取控制器
     */
    public function getController() : ?string
    {
        return $this->has('controller') ? $this->get('controller') : null;
    }


    /**
     * 添加域名
     */
    public function addDomain(string $host) : static
    {
        return $this->addDomains([$host]);
    }

    /**
     * 添加多个域名
     */
    public function addDomains(array $hosts) : static
    {
        return $this->append('domains', $hosts, false);
    }

    /**
     * 是否存在域名
     */
    public function hasDomain(string $host) : bool
    {
        return $this->has('domains') && in_array($host, $this->get('domains'));
    }

    /**
     * 获取域名
     */
    public function getDomains() : array
    {
        return $this->has('domains') ? $this->get('domains') : [];
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
            }

            if (!is_object($value)) {
                $data[$key] = $value;
            }
        }

        return $data;
    }
}