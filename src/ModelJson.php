<?php

namespace Febalist\LaravelModel;

use Illuminate\Support\Collection;

/**
 * @mixin \Eloquent
 */
trait ModelJson
{

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
        $this->attributes[$key] = $value;
    }

}
