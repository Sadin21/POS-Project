<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use App\Models\products;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportProduct implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $productCode = $row[1];
    
        $product = Product::firstOrNew(['code' => $productCode]);
        
        $product->name = $row[0];
        $product->code = $productCode;
        $product->photo = $row[2];
        $product->sale_price = $row[3];
        $product->qty += (int)$row[4];
        $product->available_qty += (int)$row[4];
        $product->category_id = (int)$row[5];
        
        $product->save();
    }
}
