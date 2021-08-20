<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DB;

class ProductController extends Component
{
    use WithFileUploads,WithPagination;

    public $name, $barcode ,$cost ,$price, $price2 ,$stock, $alerts,$mark,$model,
        $categoryid,$search, $image, $selected_id, $pageTitle, $componentName,
        $subcategoryid, $selected=[];
    
    private $pagination = 10;
    protected $paginationTheme = 'bootstrap'; 

    public function mount(){
        $this->pageTitle = 'Listado';
        $this->componentName = 'Productos';
        $this->categoryid = 'Elegir';
    }

    public function render()
    {
        if (Str::length($this->search) > 0) 
            $data = Product::join('categories as c','c.id','products.category_id')
                             ->leftJoin('sub_categories as sc','sc.id','products.subcategory_id')
                             ->select('products.*','c.name as category','sc.name as subcategory')
                             ->where('products.name','like','%' .$this->search . '%')
                             ->orWhere('products.barcode','like','%' .$this->search . '%')
                             ->orWhere('c.name','like','%' .$this->search . '%')
                             ->orderBy('products.name','asc')
                             ->paginate($this->pagination);
        else
            $data = Product::join('categories as c','c.id','products.category_id')
                            ->leftJoin('sub_categories as sc','sc.id','products.subcategory_id')
                            ->select('products.*','c.name as category','sc.name as subcategory')
                            ->orderBy('products.name','asc')
                            ->paginate($this->pagination);

        return view('livewire.products.index',[
            'products' => $data,
            'categories' => Category::orderBy('name','asc')->get(),
            'subcategories' => SubCategory::orderBy('name','asc')->get()
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function Store(){
        $rules= [
            'name' => 'required|unique:products|min:3',
            'cost' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'alerts' => 'required',
            'categoryid' => 'required|not_in:Elegir'
        ];

        $messages = [
            'name.required' => 'El nombre del producto es requerido',
            'name.unique' => 'Ya existe un producto con ese nombre',
            'name.min' => 'El nombre deve tener al menos 3 caracteres',
            'cost.required' => 'El costo es requerido',
            'price.required' => 'El precio es requerido',
            'stock.required' => 'El stock es requerido',
            'alerts.required' => 'Ingresa el valor minimo en existencias',
            'categoryid.not_in' => 'Selecciona una categoria diferente de Elegir',
        ];

        $this->validate($rules,$messages);

        $product = Product::create([
            'name' => $this->name,
            'cost' => $this->cost,
            'price' => $this->price,
            'price2' => $this->price2,
            'model' => $this->model,
            //'barcode' => $this->barcode,
            'stock' => $this->stock,
            'alerts' => $this->alerts,
            'category_id' => $this->categoryid,
            'subcategory_id' => $this->subcategoryid
        ]);

        $customFileName;
        if ($this->image) {
            $customFileName = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('public/products', $customFileName);
            $product->image = $customFileName;
            $product->save();
        }
        
        $product->barcode = date('Ymd').str_pad($product->id, 5, "0", STR_PAD_LEFT);
        $product->save();

        $this->resetUI();
        $this->emit('product-added','Producto Registrado');
    }

    public function Edit(Product $product){
        $this->selected_id = $product->id;
        $this->name = $product->name;
        $this->mark = $product->mark;
        $this->model = $product->model;
        $this->barcode = $product->barcode;
        $this->cost = $product->cost;
        $this->price = $product->price;
        $this->price2 = $product->price2;
        $this->stock = $product->stock;
        $this->alerts = $product->alerts;
        $this->categoryid = $product->category_id;
        $this->subcategoryid = $product->subcategory_id;
        $this->image = null;

        $this->emit('show-modal', 'show modal!');
    }

    public function Update(){
        $rules= [
            'name' => 'required|min:3',
            'cost' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'alerts' => 'required',
            'categoryid' => 'required|not_in:Elegir'
        ];

        $messages = [
            'name.required' => 'El nombre del producto es requerido',
            'name.min' => 'El nombre deve tener al menos 3 caracteres',
            'cost.required' => 'El costo es requerido',
            'price.required' => 'El precio es requerido',
            'stock.required' => 'El stock es requerido',
            'alerts.required' => 'Ingresa el valor minimo en existencias',
            'categoryid.not_in' => 'Selecciona una categoria diferente de Elegir',
        ];

        $this->validate($rules,$messages);

        $product = Product::find($this->selected_id);

        $product->update([
            'name' => $this->name,
            'mark' => $this->mark,
            'model' => $this->model,
            'cost' => $this->cost,
            'price' => $this->price,
            'price2' => $this->price2,
            'barcode' => $this->barcode,
            'stock' => $this->stock,
            'alerts' => $this->alerts,
            'category_id' => $this->categoryid,
            'subcategory_id' => $this->subcategoryid
        ]);

        $customFileName;
        if ($this->image) {
            $customFileName = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('public/products', $customFileName);
            $imageName = $product->image;
            $product->image = $customFileName;
            $product->save();

            if ($imageName != null) {
                if (file_exists('storage/products/' . $imageName)) {
                    unlink('storage/products/' . $imageName);
                }
            }
        }
        $this->resetUI();
        $this->emit('product-added','Producto Actualizado');
    }

    public function resetUI(){
        $this->name = '';
        $this->mark = '';
        $this->model = '';
        $this->cost = '';
        $this->price = '';
        $this->barcode = '';
        $this->stock = 0;
        $this->alerts = '';
        $this->image = null;
        $this->search = '';
        $this->categoryid = 'Elegir';
        $this->subcategoryid = null;
        $this->selected_id = 0;
    }

    protected $listeners = [
        'deleteRow' => 'Destroy'
    ];

    public function Destroy(Product $product){
        $imageTemp = $product->image;
        $product->update([
            'image' => null
        ]);
        $product->delete();

        if ($imageTemp != null) {
            if (file_exists('storage/products/' . $imageTemp)) {
                unlink('storage/products/' . $imageTemp);
            }
        }

        $this->resetUI();
        $this->emit('product-deleted','Producto Eliminado');
    }

    public function Addproducttoprintcode($state,$id){
        if ($state) {
            array_push($this->selected,$id);
        }else{
            foreach ($this->selected as $key => $value) {
                if ($value == $id) {
                    unset($this->selected[$key]);
                }
            }
        }
    }
}
