<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'description' => 'string'
    ];

    public static function getData($input)
    {
        $data = DB::table('items as a')
                    ->select(
                        'a.id', 'a.description', 'a.part_number',
                        'b.name as brand', 'c.full_name as uom',
                        'd.name as category'
                    )
                    ->join('brands as b', 'a.brand_id', '=', 'b.id')
                    ->join(
                        'unit_of_measurements as c',
                        'a.unit_of_measurement_id', '=', 'c.id'
                    )
                    ->join('categories as d', 'a.category_id', '=', 'd.id');


        if (!empty($input['keyword'])) {
            $data->where('a.description', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('a.part_number', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('b.name', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('c.full_name', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('d.name', 'LIKE', '%' . $input['keyword'] . '%');
        }

        $order = [
            'part_number' => 'a.part_number',
            'description' => 'a.description',
            'brand' => 'b.name',
            'uom' => 'c.full_name',
            'category' => 'd.name'
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
                    )
                    ->join('categories as d', 'a.category_id', '=', 'd.id');


        if (!empty($input['keyword'])) {
            $data->where('a.description', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('a.part_number', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('b.name', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('c.full_name', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('d.name', 'LIKE', '%' . $input['keyword'] . '%');
        }

        return $data->count();
    }

    public static function getAvailableItem()
    {
        $expenditure_transaction_items = DB::table('expenditure_transaction_items')
                ->select(DB::raw('item_id, SUM(amount) as b_amount'))
                ->groupBy('item_id');

        return self::whereHas('incomeTransactionItems', function (Builder $query) use ($expenditure_transaction_items)
        {
            $query->select(DB::raw('(SUM(income_transaction_items.amount) - IFNUll(SUM(b_amount), 0)) as total'))
                    ->joinSub($expenditure_transaction_items, 'expenditure_transaction_items', function ($join) {
                        $join->on('income_transaction_items.item_id', '=', 'expenditure_transaction_items.item_id');
                    })
                    ->groupBy('income_transaction_items.item_id')
                    ->having('total', '>', 0);
        })
        ->orderBy('description')
        ->get();
    }

    public static function countAvailableItemById($id)
    {
        $expenditure_transaction_items = DB::table('expenditure_transaction_items')
                ->select(DB::raw('item_id, SUM(amount) as b_amount'))
                ->groupBy('item_id');

        return self::where('id', '=', $id)
                    ->whereHas('incomeTransactionItems', function (Builder $query) use ($expenditure_transaction_items)
                    {
                        $query->select(DB::raw('(SUM(income_transaction_items.amount) - IFNUll(SUM(b_amount), 0)) as total'))
                                ->joinSub($expenditure_transaction_items, 'expenditure_transaction_items', function ($join) {
                                    $join->on('income_transaction_items.item_id', '=', 'expenditure_transaction_items.item_id');
                                })
                                ->groupBy('income_transaction_items.item_id')
                                ->having('total', '>', 0);
                    })
                    ->orderBy('description')
                    ->count();
    }

    /**
     * Get the category that owns the item.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the brand that owns the item.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the unit of measurement that owns the item.
     */
    public function unitOfMeasurement()
    {
        return $this->belongsTo(UnitOfMeasurement::class);
    }

    /**
     * Get the income transaction items for the item.
     */
    public function incomeTransactionItems()
    {
        return $this->hasMany(IncomeTransactionItem::class);
    }

    /**
     * Get the expenditure transaction items for the item.
     */
    public function expenditureTransactionItems()
    {
        return $this->hasMany(ExpenditureTransactionItem::class);
    }
}
