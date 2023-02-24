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

    public function getData($request)
    {
        if (empty($request->get('q')) && empty($request->get('order_by')) && empty($request->get('order_direction'))) {
            return self::paginate();
        }

        $data = 0;

        if (!empty($request->get('q'))) {
            $data = self::where('name', 'like', "%$request->q%")
                        ->orWhere('id', 'like', "%$request->q%");
        }

        if ($request->order_by === 'name') {
            if (!$data) {
                if ($request->order_direction !== 'asc' && $request->order_direction !== 'desc') {
                    $data = self::orderBy($request->order_by);
                } else {
                    $data = self::orderBy($request->order_by, $request->order_direction);
                }
            } else {
                if ($request->order_direction !== 'asc' && $request->order_direction !== 'desc') {
                    $data->orderBy($request->order_by);
                } else {
                    $data->orderBy($request->order_by, $request->order_direction);
                }
            }
        }

        return $data->paginate(10);
    }
}
