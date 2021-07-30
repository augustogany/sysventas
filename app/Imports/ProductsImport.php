<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithHeadingRow, WithValidation};

class ProductsImport implements ToModel
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Product([
            'name' => $row[0],
            'mark' => $row[1],
            'model' => $row[2],
            'cost' => $row[3],
            'stock' => 0,
            'category_id' => 1,
        ]);
    }
}
