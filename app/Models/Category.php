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

    public function getData($input)
    {
        $data = self::select('id', 'name');

        if (empty($input['keyword']) && empty($input['order_by']) && empty($input['order_direction']) && empty($input['page'])) {
            return $data->orderBy('name', 'asc')
                        ->offset(0)
                        ->limit(10)
                        ->get();
        }

        if (!empty($input['keyword'])) {
            $data->where('name', 'like', "%" . $input['keyword'] . "%")
                    ->orWhere('id', 'like', "%" . $input['keyword'] . "%");
        }

        if ($input['order_by'] === 'name') {
            if ($input['order_direction'] !== 'asc' && $input['order_direction'] !== 'desc') {
                $data->orderBy($input['order_by']);
            } else {
                $data->orderBy($input['order_by'], $input['order_direction']);
            }
        }

        return $data->limit(10)->get();
    }
}
