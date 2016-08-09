<?php namespace Febalist\LaravelModel;

use DB;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Jenssegers\Date\Date;

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
