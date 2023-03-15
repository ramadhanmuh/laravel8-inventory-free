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
            $data->orderBy('a.part_number', 'asc');
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
        return DB::select(
            'SELECT
                a.*,
                (IFNULL(b_amount, 0) - IFNULL(c_amount, 0)) as total
            FROM
                items as a
            LEFT JOIN
                (
                    SELECT
                        b.item_id,
                        SUM(b.amount) as b_amount
                    FROM
                        income_transaction_items as b
                    GROUP BY
                        b.item_id
                ) as x
            ON
                a.id = x.item_id
            LEFT JOIN
                (
                    SELECT
                        c.item_id,
                        SUM(c.amount) as c_amount
                    FROM
                        expenditure_transaction_items as c
                    GROUP BY
                        c.item_id
                ) as y
            ON
                a.id = y.item_id
            HAVING
                total > 0
            ORDER BY
                a.description ASC'
        );
    }

    public static function getAvailableItemIncludeIds($ids)
    {
        $query = 'SELECT a.*, (IFNULL(b_amount, 0) - IFNULL(c_amount, 0)) as total
            FROM
                items as a
            LEFT JOIN
                (
                    SELECT
                        b.item_id,
                        SUM(b.amount) as b_amount
                    FROM
                        income_transaction_items as b
                    GROUP BY
                        b.item_id
                ) as x
            ON
                a.id = x.item_id
            LEFT JOIN
                (
                    SELECT
                        c.item_id,
                        SUM(c.amount) as c_amount
                    FROM
                        expenditure_transaction_items as c
                    GROUP BY
                        c.item_id
                ) as y
            ON
                a.id = y.item_id
        ';

        $values = [];

        // $query .= 'HAVING total > 0 OR a.id = "1" ORDER BY a.description ASC';
        $query .= 'HAVING total > 0';

        foreach ($ids as $key => $value) {
            array_push($values, $value);
            
            $query .= " OR a.id = :$key ";
        }

        $query .= 'ORDER BY a.description ASC';

        return DB::select($query, $values);
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
                    ->first();
    }

    public static function getWithCategoryBrandUOMStock($input)
    {
        $where = 0;

        $values = [];
        
        $query = '
            select
                a.*, b.name as category_name,
                c.name as brand_name, d.short_name,
                x.income_transaction_items_amount,
                y.expenditure_transaction_items_amount,
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
                a.id = y.item_id
        ';

        if (!empty($input['keyword'])) {
            $query .= '
                where
                    (
                        a.part_number
                        like
                            %:keyword%
                        or
                            a.description
                        like
                            %:keyword%
                        or
                            a.price
                        like
                            %:keyword%
                        or
                            b.name
                        like
                            %:keyword%
                        or
                            c.name
                        like
                            %:keyword%
                        or
                            d.short_name
                        like
                            %:keyword%
                    )
            ';

            $where = 1;

            $values['keyword'] = $input['keyword'];
        }

        if (!empty($input['category_id'])) {
            if ($where) {
                $query .= '
                    and a.category_id = :category_id
                ';
            } else {
                $query .= '
                    where a.category_id = :category_id
                ';

                $where = 1;
            }

            $values['category_id'] = $input['category_id'];
        }

        if (!empty($input['brand_id'])) {
            if ($where) {
                $query .= '
                    and
                        a.brand_id = :brand_id
                ';
            } else {
                $query .= '
                    where a.brand_id = :brand_id
                ';

                $where = 1;
            }

            $values['brand_id'] = $input['brand_id'];
        }

        if (!empty($input['uom_id'])) {
            if ($where) {
                $query .= '
                    and
                        a.unit_of_measurement_id = :uom_id
                ';
            } else {
                $query .= '
                    where a.unit_of_measurement_id = :uom_id
                ';

                $where = 1;
            }

            $values['uom_id'] = $input['uom_id'];
        }

        $query .= '
            GROUP BY a.id
        ';

        if (is_numeric($input['start_stock'])) {
            $query .= '
                having stock > :start_stock
            ';

            $values['start_stock'] = intval($input['start_stock']) - 1;
        }

        if (is_numeric($input['end_stock'])) {
            if (is_numeric($input['start_stock'])) {
                $query .= '
                    and stock < :end_stock
                ';
            } else {
                $query .= '
                    having stock < :end_stock
                ';
            }

            $values['end_stock'] = intval($input['end_stock']) + 1;
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
                $query .= '
                    order by
                        ' . $orderColumns[$input['order_by']] . '
                    ' . $input['order_direction'] . '
                ';
            } else {
                $query .= 'order by a.part_number asc';
            }
        } else {
            $query .= 'order by a.part_number asc';
        }

        $query .= '
            limit 10 offset ' . $input['offset'] . '
        ';

        return DB::select($query, $values);
    }

    public static function countWithCategoryBrandUOMStock($input)
    {
        $where = 0;

        $values = [];

        $query = '
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
                        a.id = y.item_id
        ';

        if (!empty($input['keyword'])) {
            $query .= '
                where
                    (
                        a.part_number
                        like
                            %:keyword%
                        or
                            a.description
                        like
                            %:keyword%
                        or
                            a.price
                        like
                            %:keyword%
                        or
                            b.name
                        like
                            %:keyword%
                        or
                            c.name
                        like
                            %:keyword%
                        or
                            d.short_name
                        like
                            %:keyword%
                    )
            ';

            $where = 1;

            $values['keyword'] = $input['keyword'];
        }

        if (!empty($input['category_id'])) {
            if ($where) {
                $query .= '
                    and a.category_id = :category_id
                ';
            } else {
                $query .= '
                    where a.category_id = :category_id
                ';

                $where = 1;
            }

            $values['category_id'] = $input['category_id'];
        }

        if (!empty($input['brand_id'])) {
            if ($where) {
                $query .= '
                    and
                        a.brand_id = :brand_id
                ';
            } else {
                $query .= '
                    where a.brand_id = :brand_id
                ';

                $where = 1;
            }

            $values['brand_id'] = $input['brand_id'];
        }

        if (!empty($input['uom_id'])) {
            if ($where) {
                $query .= '
                    and
                        a.unit_of_measurement_id = :uom_id
                ';
            } else {
                $query .= '
                    where a.unit_of_measurement_id = :uom_id
                ';

                $where = 1;
            }

            $values['uom_id'] = $input['uom_id'];
        }

        if (is_numeric($input['start_stock'])) {
            $query .= '
                having stock > :start_stock
            ';

            $values['start_stock'] = intval($input['start_stock']) - 1;
        }

        if (is_numeric($input['end_stock'])) {
            if (is_numeric($input['start_stock'])) {
                $query .= '
                    and stock < :end_stock
                ';
            } else {
                $query .= '
                    having stock < :end_stock
                ';
            }

            $values['end_stock'] = intval($input['end_stock']) + 1;
        }

        $query .= ' group by a.id) as x';

        return DB::select($query, $values)[0]->total;
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
