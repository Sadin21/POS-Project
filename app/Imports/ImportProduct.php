<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\products;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportProduct implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Product([
            'name' => $row[0],
            'code' => $row[1],
            'photo' => $row[2],
            'sale_price' => $row[3],
            'qty' => (int)$row[4],
            'available_qty' => (int)$row[5],
            'category_id' => (int)$row[6],
        ]);
    }
}
