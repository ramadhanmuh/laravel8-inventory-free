<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public static function getOne()
    {
        return self::select('id', 'name', 'copyright')->limit(1)->first();
    }

    public static function updateById($input, $id)
    {
        return self::where('id', '=', $id)->update($input);
    }
}
