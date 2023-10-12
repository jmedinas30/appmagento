<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Model;

class Registry
{
    /**
     * @var array
     */
    private $registry = [];

    /**
     * @param string $id
     * @return mixed|null
     */
    public function registry($id)
    {
        if (isset($this->registry[$id])) {
            return $this->registry[$id];
        }

        return null;
    }

    /**
     * @param string $id
     * @param mixed $value
     * @param bool $graceful
     */
    public function register($id, $value, $graceful = false)
    {
        if (isset($this->registry[$id])) {
            if ($graceful) {
                return;
            }
            throw new \RuntimeException('Registry key "' . $id . '" already exists');
        }
        $this->registry[$id] = $value;
    }

    /**
     * @param string $id
     */
    public function unregister($id)
    {
        if (isset($this->registry[$id])) {
            if (is_object($this->registry[$id])
                && method_exists($this->registry[$id], '__destruct')
                && is_callable([$this->registry[$id], '__destruct'])
            ) {
                $this->registry[$id]->__destruct();
            }
            unset($this->registry[$id]);
        }
    }

    /**
     * Destruct items
     */
    public function __destruct()
    {
        $ids = array_keys($this->registry);
        array_walk($ids, [$this, 'unregister']);
    }
}
