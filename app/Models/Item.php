<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'part_number', 'category_id',
        'brand_id', 'unit_of_measurement_id',
        'price', 'image'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public static function getData($input)
    {
        $data = DB::table('items as a')
                    ->select(
                        'a.id', 'a.description', 'a.part_number',
                        'b.name as brand', 'c.full_name as uom'
                    )
                    ->join('brands as b', 'a.brand_id', '=', 'b.id')
                    ->join(
                        'unit_of_measurements as c',
                        'a.unit_of_measurement_id', '=', 'c.id'
                    );


        if (!empty($input['keyword'])) {
            $data->where('a.description', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('a.part_number', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('b.name', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('c.full_name', 'LIKE', '%' . $input['keyword'] . '%');
        }

        $order = [
            'part_number' => 'a.part_number',
            'description' => 'a.description',
            'brand' => 'b.name',
            'uom' => 'c.name'
        ];

        if (array_key_exists($input['order_by'], $order)) {
            if ($input['order_direction'] !== 'asc' && $input['order_direction'] !== 'desc') {
                $data->orderBy($order[$input['order_by']]);
            } else {
                $data->orderBy($order[$input['order_by']], $input['order_direction']);
            }
        } else {
            $data->orderBy('a.description', 'asc');
        }

        return $data->offset($input['offset'])
                        ->limit(10)
                        ->get();
    }

    public static function countData($input)
    {
        $data = DB::table('items as a')
                    ->select('a.id')
                    ->join('brands as b', 'a.brand_id', '=', 'b.id')
                    ->join(
                        'unit_of_measurements as c',
                        'a.unit_of_measurement_id', '=', 'c.id'
                    );


        if (!empty($input['keyword'])) {
            $data->where('a.description', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('a.part_number', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('b.name', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('c.full_name', 'LIKE', '%' . $input['keyword'] . '%');
        }

        return $data->count();
    }

    /**
     * Get the category that owns the item.
     */
    public function post()
    {
        return $this->belongsTo(Category::class);
    }
}
