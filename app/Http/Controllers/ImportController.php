<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ProductsImport;

class ImportController extends Controller
{
    public function import()
    {
        (new ProductsImport)->import('products.csv');
        
        return redirect('/')->with('success', 'File imported successfully!');
    }
}
