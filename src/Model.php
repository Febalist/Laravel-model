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

    protected function mutateAttribute($key, $value)
    {
        if ($this->hasCast($key)) {
            $value = $this->castAttribute($key, $value);
            if ($this->getCastType($key) == 'array') {
                $value = $value ?: [];
            }
        }
        $value = parent::mutateAttribute($key, $value);
        return $value;
    }

}
