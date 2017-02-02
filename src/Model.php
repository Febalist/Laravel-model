<?php

namespace Febalist\LaravelModel;

use Eloquent;
use Illuminate\Support\Collection;

/**
 * @property integer id
 */
class Model extends Eloquent
{
    protected $guarded = [];

    public static function remove($ids = [])
    {
        $ids = array_filter($ids);
        $ids = array_unique($ids);
        if (count($ids) > 0) {
            return static::whereIn('id', $ids)->delete();
        }
        return 0;
    }

    public static function removeAll()
    {
        return static::where(true)->delete();
    }

    protected function setAttributeToArray($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    protected function getJson($key, $default = null)
    {
        $value = $this->getAttributeFromArray($key);
        if (is_null($value)) {
            return $default;
        }
        return $this->fromJson($value);
    }

    protected function setJson($key, $value)
    {
        if ($value instanceof Collection) {
            $value = $value->toJson();
        } else {
            $value = $this->asJson($value);
        }
        $this->setAttributeToArray($key, $value);
    }

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
        $list = implode($delimiter, $list);
        $this->setAttributeToArray($key, $list);
    }
}
