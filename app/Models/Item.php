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
        $query = "
            select
                a.*, b.name as category_name,
                c.name as brand_name, d.short_name,
                (IFNULL(x.income_transaction_items_amount, 0) -
                IFNULL(y.expenditure_transaction_items_amount, 0))
                as stock
            from
                items as a
            inner join
                categories as b
            on
                a.category_id = b.id
            inner join
                brands as c
            on
                a.brand_id = c.id
            inner join
                unit_of_measurements as d
            on
                a.unit_of_measurement_id = d.id
            left join
                (
                    select
                        SUM(amount) as income_transaction_items_amount,
                        item_id
                    from
                        income_transaction_items
                    group by
                        item_id
                ) as x
            on
                a.id = x.item_id
            left join
                (
                    select
                        SUM(amount) as expenditure_transaction_items_amount,
                        item_id
                    from
                        expenditure_transaction_items
                    group by
                        item_id
                ) as y
            on
                a.id = x.item_id
        ";

        if (!empty($input['keyword'])) {
            $query .= "
                where
                    a.part_number
                like
                    '%:keyword%'
                or
                    a.description
                like
                    '%:keyword%'
                or
                    a.price
                like
                    '%:keyword%'
                or
                    b.name
                like
                    '%:keyword%'
                or
                    c.name
                like
                    '%:keyword%'
                or
                    d.short_name
                like
                    '%:keyword%'
            ";
        }

        if ($input['start_stock'] !== '') {
            $query .= "
                having stock > " . (intval($input['start_stock']) - 1) . "
            ";
        }

        if ($input['end_stock'] !== '') {
            if ($input['start_stock'] !== '') {
                $query .= "
                    and stock < " . (intval($input['end_stock']) + 1) . "
                ";
            } else {
                $query .= "
                    having stock < " . (intval($input['end_stock']) + 1) . "
                ";
            }
        }

        $orderColumns = [
            'part_number' => 'a.part_number',
            'description' => 'a.description',
            'price' => 'a.price',
            'category' => 'b.name',
            'brand' => 'c.name',
            'uom' => 'd.short_name',
        ];

        if (array_key_exists($input['order_by'], $orderColumns)) {
            if ($input['order_direction'] === 'asc' || $input['order_direction'] === 'desc') {
                $query .= "
                    order by
                        " . $orderColumns[$input['order_by']] . "
                    " . $input['order_direction'] . "
                ";
            } else {
                $query .= "order by a.part_number asc";
            }
        } else {
            $query .= "order by a.part_number asc";
        }

        $query .= "
            limit 10 offset " . $input['offset'] . "
        ";

        return DB::select($query, ['keyword' => $input['keyword']]);
    }

    public static function countWithCategoryBrandUOMStock($input)
    {
        $query = "
            select
                count(*) as total
            from
                (
                    select
                        (IFNULL(x.income_transaction_items_amount, 0) -
                        IFNULL(y.expenditure_transaction_items_amount, 0))
                        as stock
                    from
                        items as a
                    inner join
                        categories as b
                    on
                        a.category_id = b.id
                    inner join
                        brands as c
                    on
                        a.brand_id = c.id
                    inner join
                        unit_of_measurements as d
                    on
                        a.unit_of_measurement_id = d.id
                    left join
                        (
                            select
                                SUM(amount) as income_transaction_items_amount,
                                item_id
                            from
                                income_transaction_items
                            group by
                                item_id
                        ) as x
                    on
                        a.id = x.item_id
                    left join
                        (
                            select
                                SUM(amount) as expenditure_transaction_items_amount,
                                item_id
                            from
                                expenditure_transaction_items
                            group by
                                item_id
                        ) as y
                    on
                        a.id = x.item_id
        ";

        if (!empty($input['keyword'])) {
            $query .= "
                where
                    a.part_number
                like
                    '%:keyword%'
                or
                    a.description
                like
                    '%:keyword%'
                or
                    a.price
                like
                    '%:keyword%'
                or
                    b.name
                like
                    '%:keyword%'
                or
                    c.name
                like
                    '%:keyword%'
                or
                    d.short_name
                like
                    '%:keyword%'
            ";
        }

        if ($input['start_stock'] !== '') {
            $query .= "
                having stock > " . (intval($input['start_stock']) - 1) . "
            ";
        }

        if ($input['end_stock'] !== '') {
            if ($input['start_stock'] !== '') {
                $query .= "
                    and stock < " . (intval($input['end_stock']) + 1) . "
                ";
            } else {
                $query .= "
                    having stock < " . (intval($input['end_stock']) + 1) . "
                ";
            }
        }

        $query .= ") as a";

        return DB::select($query, ['keyword' => $input['keyword']])[0]->total;
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
