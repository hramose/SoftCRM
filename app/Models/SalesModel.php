<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SalesModel extends Model
{
    /**
     * table name
     */
    protected $table = 'sales';
    /**
     * @param $allInputs
     * @return mixed
     */
    public function insertRow($allInputs)
    {
        return SalesModel::insertGetId(
            [
                'name' => $allInputs['name'],
                'quantity' => $allInputs['quantity'],
                'date_of_payment' => $allInputs['date_of_payment'],
                'product_id' => $allInputs['product_id'],
                'created_at' => Carbon::now(),
                'is_active' => 1
            ]
        );
    }

    /**
     * @param $id
     * @param $allInputs
     * @return mixed
     */
    public function updateRow($id, $allInputs)
    {
        return SalesModel::where('id', '=', $id)->update(
            [
                'name' => $allInputs['name'],
                'quantity' => $allInputs['quantity'],
                'date_of_payment' => $allInputs['date_of_payment'],
                'product_id' => $allInputs['product_id'],
                'updated_at' => Carbon::now(),
                'is_active' => 1
            ]);
    }

    /**
     * @param $rulesType
     * @return array
     */
    public function getRules($rulesType)
    {
        switch ($rulesType) {
            case 'STORE':
                return [
                    'name' => 'required',
                    'quantity' => 'required',
                    'product_id' => 'required',
                    'date_of_payment' => 'required',
                ];
        }
    }

    /**
     * @param $id
     * @param $activeType
     * @return bool
     */
    public function setActive($id, $activeType)
    {
        $findSalesById = SalesModel::where('id', '=', $id)->update(
            [
                'is_active' => $activeType
            ]);

        if ($findSalesById) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * @return int
     */
    public static function countSales()
    {
        return SalesModel::all()->count();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function products()
    {
        return $this->belongsTo(ProductsModel::class, 'product_id');
    }

    /**
     * @param $type
     * @param $value
     * @param int $paginationLimit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function trySearchSalesByValue($type, $value, $paginationLimit = 10)
    {
        return SalesModel::where($type, 'LIKE', '%' . $value . '%')->paginate($paginationLimit);
    }
}
