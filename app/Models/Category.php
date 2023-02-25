<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'id'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'string'
    ];

    public static function getData($input)
    {
        $data = self::select('id', 'name');

        if (!empty($input['keyword'])) {
            $data->where('id', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('name', 'LIKE', '%' . $input['keyword'] . '%');
        }

        if ($input['order_by'] === 'name') {
            if ($input['order_direction'] !== 'asc' && $input['order_direction'] !== 'desc') {
                $data->orderBy($input['order_by']);
            } else {
                $data->orderBy($input['order_by'], $input['order_direction']);
            }
        } else {
            $data->orderBy('name', 'asc');
        }

        return $data->offset($input['offset'])
                        ->limit(10)
                        ->get();
    }

    public static function countData($input)
    {
        $data = self::select('id');

        if (!empty($input['keyword'])) {
            $data->where('name', 'like', "%" . $input['keyword'] . "%")
                    ->orWhere('id', 'like', "%" . $input['keyword'] . "%");
        }

        return $data->count();
    }

    /**
     * Get the items for the category.
     */
    public function comments()
    {
        return $this->hasMany(Item::class);
    }
}
