<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithHeadingRow, WithValidation};

class ProductsImport implements ToModel,WithHeadingRow
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
         $product = Product::create([
            'name' => $row['name'],
            'model' => $row['model'],
            'stock' => $row['stock'],
            'price' => 3,
            'price2' => 3.3,
            'image' => 'unavailable.png',
            'category_id' => 1,
        ]);
        $product->barcode = date('Ymd').str_pad($product->id, 5, "0", STR_PAD_LEFT);
        $product->save();
        return $product;
    }
}
