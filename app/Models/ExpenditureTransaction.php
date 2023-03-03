<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenditureTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'picker',
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
            'id', 'picker', 'reference_number', 'remarks', 'created_at',
        );

        if (!empty($input['keyword'])) {
            $data->where(function ($query) use ($input) {
                $query->where('picker', 'LIKE', '%' . $input['keyword'] . '%')
                        ->orWhere('reference_number', 'LIKE', '%' . $input['keyword'] . '%')
                        ->orWhere('remarks', 'LIKE', '%' . $input['keyword'] . '%');
            });
        }

        if (!empty($input['start_date']) && !empty($input['end_date'])) {
            $data->whereBetween('created_at', [
                $input['start_date'] - 86400,
                $input['end_date'] + 86400
            ]);
        } else {
            if (!empty($input['start_date'])) {
                $data->where('created_at', '>', $input['start_date'] - 86400);
            }

            if (!empty($input['end_date'])) {
                $data->where('created_at', '<', $input['end_date'] + 86400);
            }
        }

        $orders = [
            'picker', 'reference_number', 'remarks', 'created_at'
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
            $data->where(function ($query) use ($input) {
                $query->where('picker', 'LIKE', '%' . $input['keyword'] . '%')
                        ->orWhere('reference_number', 'LIKE', '%' . $input['keyword'] . '%')
                        ->orWhere('remarks', 'LIKE', '%' . $input['keyword'] . '%');
            });
        }

        if (!empty($input['start_date']) && !empty($input['end_date'])) {
            $data->whereBetween('created_at', [
                $input['start_date'] - 86400,
                $input['end_date'] + 86400
            ]);
        } else {
            if (!empty($input['start_date'])) {
                $data->where('created_at', '>', $input['start_date'] - 86400);
            }

            if (!empty($input['end_date'])) {
                $data->where('created_at', '<', $input['end_date'] + 86400);
            }
        }

        return $data->count();
    }

    /**
     * Get the expenditure transaction items for the expenditure trasaction.
     */
    public function expenditureTransactionItems()
    {
        return $this->hasMany(ExpenditureTransactionItem::class);
    }
}
