<?php namespace Febalist\LaravelModel;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Collection;
use Jenssegers\Date\Date;

/**
 * @property integer id
 * @property Date    created_at
 * @property Date    updated_at
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder whereIn($column, $values, $boolean = 'and', $not = false)
 * @method static $this find($id, $columns = ['*'])
 * @method static $this findMany($ids, $columns = ['*'])
 * @method static $this findOrFail($id, $columns = ['*'])
 * @method static $this findOrNew($id, $columns = ['*'])
 * @method static $this first($columns = ['*'])
 * @method static $this firstOrFail($columns = ['*'])
 * @method static $this firstOrNew(array $attributes)
 * @method static $this firstOrCreate(array $attributes, array $values = [])
 * @method static $this updateOrCreate(array $attributes, array $values = [])
 * @method static Collection pluck($column, $key = null)
 * @method static $this chunk($count, callable $callback)
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

    protected function asDateTime($value)
    {
        $value = parent::asDateTime($value);
        return Date::parse($value);
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

    protected function getList($key, $delimiter = ',')
    {
        $list = $this->getAttributeFromArray($key);
        return explode($delimiter, $list);
    }

    protected function setList($key, $list, $delimiter = ',', $transform = null, $arguments = null)
    {
        $arguments = func_get_args();
        $arguments = array_slice($arguments, 3);
        $list      = list_cleanup($list, function ($element) use ($transform, $arguments) {
            $element = trim($element);
            if ($transform) {
                $arguments[0] = $element;
                $element      = call_user_func_array($transform, $arguments);
            }
            return $element;
        });
        $list      = implode($delimiter, $list);
        $this->setAttributeToArray($key, $list);
    }
}
