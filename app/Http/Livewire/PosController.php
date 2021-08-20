<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Denomination;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Type;
use App\Models\Contact;
use App\Models\SaleDetail;
use DB;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Exception;

class PosController extends Component
{
    public $total, $itemsQuantity,$efectivo,$change,$typedocument_id,
           $contact_id, $nit,$xmayor;

    // propiedades para la creacion del customer if not exists
    public $name, $lastName, $fullName, $custom_typedocument, $custom_nit, $custom_phone,
           $componentName, $selected_id;
    
    public function mount(){
        $this->efectivo = 0;
        $this->change = 0;
        $this->xmayor = 1;
        $this->total = Cart::getTotal();
        $this->itemsQuantity = Cart::getTotalQuantity();

        //datos para el customers$this->typedocument_id = 'Elegir';
        $this->typedocument_id = 'Elegir';
        $this->custom_typedocument = 'Elegir';
        $this->componentName = 'Crear Cliente';
    }

    public function render()
    {
        return view('livewire.pos.pos',[
                'denominations' => Denomination::orderBy('value','desc')->get(),
                'documentos' => Type::where('tipo','docidentidad')->get(),
                'cart' => Cart::getContent()->sortBy('name')
                ])
               ->extends('layouts.theme.app')
               ->section('content');
    }

    public function ACash($value){
        $this->efectivo += ($value == 0 ? $this->total : $value);
        $this->change = ($this->efectivo - $this->total);
    }

    protected $listeners = [
        'scan-code' => 'ScanCode',
        'removeItem',
        'clearCart',
        'saveSale'
    ];

    public function ScanCode($barcode,$cant =1){
        $product = Product::where('barcode',$barcode)->first();

        if ($product == null || empty($product)) {
            $this->emit('scan-notfound','El producto no esta registrado');
        }else{
            if ($product->stock < 1) {
                $this->emit('no-stock','Stock insuficiente :(');
                return;
            }
            if ($this->InCart($product->id)) {
                $this->increaseQty($product->id);
                return;
            }
            
            Cart::add($product->id, $product->name,$product->price, $cant, [$product->image,$product->barcode]);
            //dd(Cart::getContent());
            $this->total = Cart::getTotal();
            $this->itemsQuantity = Cart::getTotalQuantity();

            $this->emit('scan-ok','Producto agregado');
        }
        
    }
    public function InCart($productid){
        $exist = Cart::get($productid);
        return $exist ? true : false;
    }
    
    public function increaseQty($productId,$cant = 0){
        $title = 'Cantidad actualizada';
        $product = Product::findOrFail($productId);
        $exist = Cart::get($productId);
        
        if ($product->stock < ($cant + $exist->quantity)) {
            $this->emit('no-stock', 'Stock insuficiente :(');
            return;
        }
        $cant ++;
        Cart::update($exist->id,array('quantity' => $cant));
        //Cart::update($exist->id,tarray($product->id,$product->name, $product->price, $cant, $product->image));
        $this->total = Cart::getTotal();
        $this->itemsQuantity = Cart::getTotalQuantity();
        $this->emit('scan-ok', $title);
    }

    public function increaseQtyorg($productId,$cant = 1){
        $title = '';
        $product = Product::findOrFail($productId);
        $exist = Cart::get($productId);
        
        if ($exist) {
            $title = 'Cantidad actualizada';
        } else {
            $title = 'Producto agregado';
        }

        if ($exist) {
            if ($product->stock < ($cant + $exist->quantity)) {
                $this->emit('no-stock', 'Stock insuficiente :(');
                return;
            }
        }
        Cart::add($product->id,$product->name, $product->price, $cant, $product->image);
        $this->total = Cart::getTotal();
        $this->itemsQuantity = Cart::getTotalQuantity();
        $this->emit('scan-ok', $title);
    }

    public function updateQty($productId, $cant = 1){
        $product = Product::findOrFail($productId);
        $exist = Cart::get($productId);

        if ($exist) {
            $title = 'Cantidad actualizada';
        } else {
            $title = 'Producto agregado';
        }
        if ($exist) {
            if ($product->stock < $cant ) {
                $this->emit('no-stock', 'Stock insuficiente :(');
                return;
            }
        }
       
        if ($cant > 0) {
            Cart::update($exist->id, array(
                    'quantity' => array(
                        'relative' => false,
                        'value' => $cant
                    ),
            ));
            $this->total = Cart::getTotal();
            $this->itemsQuantity = Cart::getTotalQuantity();
            $this->emit('scan-ok', $title);
        }else{
            $this->removeItem($productId);
        }
    }

    public function updateQtyorg($productId, $cant = 1){
        $product = Product::findOrFail($productId);
        $exist = Cart::get($productId);

        if ($exist) {
            $title = 'Cantidad actualizada';
        } else {
            $title = 'Producto agregado';
        }
        if ($exist) {
            if ($product->stock < $cant ) {
                $this->emit('no-stock', 'Stock insuficiente :(');
                return;
            }
        }
       
        $this->removeItem($productId);
        if ($cant > 0) {
            Cart::add($product->id,$product->name, $product->price, $cant, $product->image);
            $this->total = Cart::getTotal();
            $this->itemsQuantity = Cart::getTotalQuantity();
            $this->emit('scan-ok', $title);
        }
    }

    public function removeItem($productId){
        Cart::remove($productId);
        $this->total= Cart::getTotal();
        $this->itemsQuantity = Cart::getTotalQuantity();
        $this->emit('scan-ok', 'Producto eliminado');
    }

    public function decreaseQty($productId){

        $item = Cart::get($productId);
       
        if ($item->quantity <= 1){ 
            $this->removeItem($productId);
            return;
        }
        Cart::update($item->id,array('quantity' => -1));
        
        $this->total = Cart::getTotal();
        $this->ItemsQuantity = Cart::getTotalQuantity();
     
        $this->emit('scan-ok', 'Cantidad Actualizada');
    }

    public function decreaseQtyorg($productId){

        $item = Cart::get($productId);

        Cart::remove($productId);

        $newQty=($item->quantity)-1;

        if($newQty > 0)
        
            Cart::add($item->id,$item->name, $item->price, $newQty, $item->attributes[0]);
        
        $this->total = Cart::getTotal();
        $this->ItemsQuantity = Cart::getTotalQuantity();
     
        $this->emit('scan-ok', 'Cantidad Actualizada');
    }

    public function clearCart(){
        Cart::clear();
        $this->efectivo = 0;
        $this->change = 0;
        $this->xmayor = false;
        $this->total= Cart::getTotal();
        $this->itemsQuantity = Cart::getTotalQuantity();
        $this->emit('scan-ok', 'Carrito vacio');
    }

    public function saveSale(){
       
        if ($this->total <= 0) {
            $this->emit('sale-error','AGREGA PRODUCTOS A LA VENTA');
            return;
        }
        if ($this->efectivo <= 0) {
            $this->emit('sale-error','INGRESA EL EFECTIVO');
            return;
        }
        if ($this->total > $this->efectivo) {
            $this->emit('sale-error','EL EFECTIVO DEBE SER MAYOR O IGUAL AL TOTAL');
            return;
        }

        DB::beginTransaction();
        try {
            $sale = Sale::create([
                'total' => $this->total,
                'items' => $this->itemsQuantity,
                'cash' => $this->efectivo,
                'change' => $this->change,
                'contact_id' => $this->contact_id,
                'user_id' => auth()->user()->id
            ]);

            if ($sale) {
                $items = Cart::getContent();
                foreach ($items as $item) {
                    SaleDetail::create([
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                        'product_id' => $item->id,
                        'sale_id' => $sale->id
                    ]);
                    //update stock
                    $product = Product::find($item->id);
                    $product->stock = $product->stock - $item->quantity;
                    $product->save();
                }
            }
            DB::commit();
            Cart::clear();
            $this->efectivo = 0;
            $this->change = 0;
            $this->xmayor = false;
            $this->total= Cart::getTotal();
            $this->itemsQuantity = Cart::getTotalQuantity();
            $this->emit('sale-ok', 'Venta Registrada con exito');
            $this->emit('print-ticket',$sale->id);

        } catch (Exception $e) {
            DB::rollback();
            $this->emit('sale-error',$e->getMessage());
        }
    }

    public function resetUI(){
        $this->name = '';
        $this->lastName = '';
        $this->fullName = '';
        $this->custom_typedocument = 'Elegir';
        $this->custom_nit = '';
        $this->custom_phone = '';
    }

    public function Store(){
        $rules= [
            'name' => 'required',
            'custom_typedocument' => 'required|not_in:Elegir',
            'custom_nit' => 'required|unique:contacts,ci|min:3'
        ];

        $messages = [
            'name.required' => 'El nombre es requerido',
            'custom_nit.required' => 'El ci|nit es requerido',
            'ci.unique' => 'Ya existe un ci|nit con ese numero',
            'ci.min' => 'El ci deve tener al menos 3 caracteres',
            'custom_typedocument.required' => 'El tipo de documento es requerido',
            'custom_typedocument.not_in' => 'Selecciona un tipo de documento diferente de Elegir'
        ];

        $this->validate($rules,$messages);

        Contact::create([
            'name' => $this->name,
            'lastName' => $this->lastName,
            'fullName' => $this->name.' '.$this->lastName,
            'typedocument_id' => $this->custom_typedocument,
            'ci' => $this->custom_nit,
            'phone' => $this->custom_phone,
            'type' => 'customer'
        ]);

        $this->resetUI();
        $this->emit('customer-added','Cliente Registrado exitosamente');
    }

    public function printTicket($sale){
         return Redirect::to("print://$sale->id");
    }

    public function xMayor($state,$id){
       
        if ($state) {
            $product = Product::findOrFail($id);
            
            Cart::update($id, array(
                'price' => $product->price2, // new item price, price can also be a string format like so: '98.67'
              ));
            $this->total = Cart::getTotal();
            $this->itemsQuantity = Cart::getTotalQuantity();
            $this->emit('scan-ok', 'Precio Actualizado');
            
        }else{
            $product = Product::findOrFail($id);

            Cart::update($id, array(
                'price' => $product->price, // new item price, price can also be a string format like so: '98.67'
              ));
            $this->total = Cart::getTotal();
            $this->itemsQuantity = Cart::getTotalQuantity();
            $this->emit('scan-ok', 'Precio Actualizado');
        }
    }
}
