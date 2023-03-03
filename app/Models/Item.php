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
            $query->select(DB::raw('(IFNULL(SUM(income_transaction_items.amount), 0) - IFNUll(SUM(b_amount), 0)) as total'))
                    ->joinSub($expenditure_transaction_items, 'expenditure_transaction_items', function ($join) {
                        $join->on('income_transaction_items.item_id', '=', 'expenditure_transaction_items.item_id');
                    })
                    ->groupBy('income_transaction_items.item_id')
                    ->having('total', '>', 0);
        })
        ->orderBy('description')
        ->get();
    }

    public static function getAvailableItemIncludeIds($ids)
    {
        $expenditure_transaction_items = DB::table('expenditure_transaction_items')
                ->select(DB::raw('item_id, SUM(amount) as b_amount'))
                ->groupBy('item_id');

        $query = self::whereHas('incomeTransactionItems', function (Builder $query) use ($expenditure_transaction_items)
        {
            $query->select(DB::raw('(SUM(income_transaction_items.amount) - IFNUll(SUM(b_amount), 0)) as total'))
                    ->joinSub($expenditure_transaction_items, 'expenditure_transaction_items', function ($join) {
                        $join->on('income_transaction_items.item_id', '=', 'expenditure_transaction_items.item_id');
                    })
                    ->groupBy('income_transaction_items.item_id')
                    ->having('total', '>', 0);
        });

        foreach ($ids as $key => $value) {
            $query->orWhere('id', '=', $value);
        }

        return $query->orderBy('description')
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

    public static function getStockById($id)
    {
        $income_transaction_items = DB::table('income_transaction_items')
                                        ->select(DB::raw(
                                            'SUM(amount) as income_transaction_items_amount,' .
                                            'item_id'
                                        ))
                                        ->groupBy('item_id');

        $expenditure_transaction_items = DB::table('expenditure_transaction_items')
                                            ->select(DB::raw(
                                                'SUM(amount) as expenditure_transaction_items_amount,' .
                                                'item_id'
                                            ))
                                            ->groupBy('item_id');
        return DB::table('items as a')
                    ->select(
                        DB::raw(
                            'a.id, a.description, a.part_number,' .
                            '(IFNULL(income_transaction_items.income_transaction_items_amount, 0) -' .
                            'IFNULL(expenditure_transaction_items.expenditure_transaction_items_amount, 0)) as total'
                        )
                    )
                    ->leftJoinSub($income_transaction_items, 'income_transaction_items', function ($join) {
                        $join->on('a.id', '=', 'income_transaction_items.item_id');
                    })
                    ->leftJoinSub($expenditure_transaction_items, 'expenditure_transaction_items', function ($join) {
                        $join->on('a.id', '=', 'expenditure_transaction_items.item_id');
                    })
                    ->where('id', '=', $id)
                    ->groupBy('a.id')
                    ->get();
    }

    public static function getWithCategoryBrandUOMStock($input)
    {
        $income_transaction_items = DB::table('income_transaction_items')
                                        ->select(DB::raw(
                                            'SUM(amount) as income_transaction_items_amount,' .
                                            'item_id'
                                        ))
                                        ->groupBy('item_id');

        $expenditure_transaction_items = DB::table('expenditure_transaction_items')
                                            ->select(DB::raw(
                                                'SUM(amount) as expenditure_transaction_items_amount,' .
                                                'item_id'
                                            ))
                                            ->groupBy('item_id');
                                            
        $data = self::select(DB::raw(
            'items.*, categories.name as category_name, brands.name as brand_name,' .
            'unit_of_measurements.short_name,' .
            '(IFNULL(income_transaction_items.income_transaction_items_amount, 0) -' .
            'IFNULL(expenditure_transaction_items.expenditure_transaction_items_amount, 0)) as stock'
        ))
        ->join(
            'categories', 'items.category_id', '=', 'categories.id'
        )
        ->join(
            'brands', 'items.brand_id', '=', 'brands.id'
        )
        ->join(
            'unit_of_measurements', 'items.unit_of_measurement_id', '=', 'unit_of_measurements.id'
        )
        ->leftJoinSub($income_transaction_items, 'income_transaction_items', function ($join) {
            $join->on('items.id', '=', 'income_transaction_items.item_id');
        })
        ->leftJoinSub($expenditure_transaction_items, 'expenditure_transaction_items', function ($join) {
            $join->on('items.id', '=', 'expenditure_transaction_items.item_id');
        });

        if (!empty($input['keyword'])) {
            $data->where('items.part_number', 'like', '%' . $input['keyword'] . '%')
                    ->orWhere('items.description', 'like', '%' . $input['keyword'] . '%')
                    ->orWhere('items.price', 'like', '%' . str_replace('.', '', $input['keyword']) . '%')
                    ->orWhere('categories.name', 'like', '%' . $input['keyword'] . '%')
                    ->orWhere('brands.name', 'like', '%' . $input['keyword'] . '%')
                    ->orWhere('unit_of_measurements.short_name', 'like', '%' . $input['keyword'] . '%');
        }

        $orderColumns = [
            'part_number' => 'items.part_number',
            'description' => 'items.description',
            'price' => 'items.price',
            'category' => 'categories.name',
            'brand' => 'brands.name',
            'uom' => 'unit_of_measurement.short_name',
        ];

        if (array_key_exists($input['order_by'], $orderColumns)) {
            if ($input['order_direction'] === 'asc' || $input['order_direction'] === 'desc') {
                $data->orderBy($orderColumns[$input['order_by']], $input['order_direction']);
            } else {
                $data->orderBy('items.part_number', 'asc');
            }
        } else {
            $data->orderBy('items.part_number', 'asc');
        }

        if (!empty($input['start_stock'])) {
            $data->having('stock', '>', intval($input['start_stock']) - 1);
        }

        if (!empty($input['end_stock'])) {
            $data->having('stock', '<', intval($input['end_stock']) + 1);
        }

        return $data->offset($input['offset'])
                        ->limit(10)
                        ->get();
    }

    public static function countWithCategoryBrandUOMStock($input)
    {
        $income_transaction_items = DB::table('income_transaction_items')
                                        ->select(DB::raw(
                                            'SUM(amount) as income_transaction_items_amount,' .
                                            'item_id'
                                        ))
                                        ->groupBy('item_id');

        $expenditure_transaction_items = DB::table('expenditure_transaction_items')
                                            ->select(DB::raw(
                                                'SUM(amount) as expenditure_transaction_items_amount,' .
                                                'item_id'
                                            ))
                                            ->groupBy('item_id');
                                            
        $data = self::select(DB::raw(
            'items.*, categories.name as category_name, brands.name as brand_name,' .
            'unit_of_measurements.short_name,' .
            '(IFNULL(income_transaction_items.income_transaction_items_amount, 0) -' .
            'IFNULL(expenditure_transaction_items.expenditure_transaction_items_amount, 0)) as stock'
        ))
        ->join(
            'categories', 'items.category_id', '=', 'categories.id'
        )
        ->join(
            'brands', 'items.brand_id', '=', 'brands.id'
        )
        ->join(
            'unit_of_measurements', 'items.unit_of_measurement_id', '=', 'unit_of_measurements.id'
        )
        ->leftJoinSub($income_transaction_items, 'income_transaction_items', function ($join) {
            $join->on('items.id', '=', 'income_transaction_items.item_id');
        })
        ->leftJoinSub($expenditure_transaction_items, 'expenditure_transaction_items', function ($join) {
            $join->on('items.id', '=', 'expenditure_transaction_items.item_id');
        });

        if (!empty($input['keyword'])) {
            $data->where('items.part_number', 'like', '%' . $input['keyword'] . '%')
                    ->orWhere('items.description', 'like', '%' . $input['keyword'] . '%')
                    ->orWhere('items.price', 'like', '%' . str_replace('.', '', $input['keyword']) . '%')
                    ->orWhere('categories.name', 'like', '%' . $input['keyword'] . '%')
                    ->orWhere('brands.name', 'like', '%' . $input['keyword'] . '%')
                    ->orWhere('unit_of_measurements.short_name', 'like', '%' . $input['keyword'] . '%');
        }

        if (!empty($input['start_stock'])) {
            $data->having('stock', '>', intval($input['start_stock']) - 1);
        }

        if (!empty($input['end_stock'])) {
            $data->having('stock', '<', intval($input['end_stock']) + 1);
        }

        return $data->count();
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
