<?php namespace Febalist\LaravelModel;

use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Jenssegers\Date\Date;

/**
 * @property integer id
 * @property Date    created_at
 * @property Date    updated_at
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static $this find($id, $columns = ['*'])
 * @method static $this findMany($ids, $columns = ['*'])
 * @method static $this findOrFail($id, $columns = ['*'])
 * @method static $this findOrNew($id, $columns = ['*'])
 * @method static $this first($columns = ['*'])
 * @method static $this firstOrFail($columns = ['*'])
 * @method static $this firstOrNew(array $attributes)
 * @method static $this firstOrCreate(array $attributes, array $values = [])
 * @method static $this updateOrCreate(array $attributes, array $values = [])
 * @method static $this chunk($count, callable $callback)
 */
class Model extends Eloquent
{

    protected $guarded = [];

    public static function clear($ids = [])
    {
        $instance = new static;
        $table    = $instance->getTable();
        $query    = DB::table($table);
        if ($ids) {
            $query->whereIn('id', $ids);
        }
        return $query->delete();
    }

    protected function asDateTime($value)
    {
        $value = parent::asDateTime($value);
        return Date::parse($value);
    }

}
