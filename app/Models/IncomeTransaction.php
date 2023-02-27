<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'supplier',
        'reference_number',
        'remarks',
        'created_at'
    ];

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public static function getData($input)
    {
        $data = self::select(
            'id', 'supplier', 'reference_number', 'remarks', 'created_at',
        );

        if (!empty($input['keyword'])) {
            $data->where('supplier', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('reference_number', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('remarks', 'LIKE', '%' . $input['keyword'] . '%');
        }

        $orders = [
            'supplier', 'reference_number', 'remarks', 'created_at'
        ];

        if (in_array($input['order_by'], $orders)) {
            if ($input['order_direction'] !== 'asc' && $input['order_direction'] !== 'desc') {
                $data->orderBy($input['order_by']);
            } else {
                $data->orderBy($input['order_by'], $input['order_direction']);
            }
        } else {
            $data->orderBy('created_at', 'desc');
        }

        return $data->offset($input['offset'])
                        ->limit(10)
                        ->get();
    }

    public static function countData($input)
    {
        $data = self::select('id');

        if (!empty($input['keyword'])) {
            $data->where('supplier', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('reference_number', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('remarks', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('created_at', 'LIKE', '%' . $input['keyword'] . '%');
        }

        return $data->count();
    }

    /**
     * Get the income transaction items for the income trasaction.
     */
    public function incomeTransactionItems()
    {
        return $this->hasMany(IncomeTransactionItem::class);
    }
}
