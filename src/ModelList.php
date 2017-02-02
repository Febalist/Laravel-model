<?php

namespace Febalist\LaravelModel;

/**
 * @mixin \Eloquent
 */
trait ModelList
{

    protected function getList($key, $delimiter = ',', $callback = null, $arguments = [])
    {
        $list = $this->getAttributeFromArray($key);
        if ($callback) {
            $list = array_map_args($list, $callback, $arguments);
        }
        $list = explode($delimiter, $list);
        return array_filter($list);
    }

    protected function setList($key, $list, $delimiter = ',', $callback = null, $arguments = [])
    {
        $list = list_cleanup($list, 'trim');
        if ($callback) {
            $list = array_map_args($list, $callback, $arguments);
            $list = list_cleanup($list);
        }
        $list                   = implode($delimiter, $list);
        $this->attributes[$key] = $list;
    }

}
