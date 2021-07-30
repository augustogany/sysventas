<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\SubCategory;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class SubCategoryController extends Component
{
    use WithFileUploads,WithPagination;

    public $name, $image, $search ,$selected_id, $pageTitle, $componentName,
           $categoryid;
    private $pagination = 5;
    protected $paginationTheme = 'bootstrap'; 

    public function mount(){
        $this->pageTitle = 'Listado';
        $this->componentName = 'Sub Categorias';
        $this->categoryid = 'Elegir';
    }

    public function render()
    {
        if (strlen($this->search) > 0) 
        $data = SubCategory::join('categories as c','c.id','sub_categories.category_id')
                             ->select('sub_categories.*','c.name as category')
                             ->where('sub_categories.name','like','%' .$this->search. '%')
                             ->orWhere('c.name','like','%' .$this->search . '%')
                             ->orderBy('sub_categories.name','asc')
                             ->paginate($this->pagination);
    else
    $data = SubCategory::join('categories as c','c.id','sub_categories.category_id')
                        ->select('sub_categories.*','c.name as category')
                        ->orderBy('sub_categories.name','asc')
                        ->paginate($this->pagination);

    return view('livewire.subcategories.index',
            [
                'subcategories'=>$data,
                'categories' => Category::orderBy('name','asc')->get()
            ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function Store(){
        $rules= [
            'name' => 'required|unique:sub_categories|min:3',
            'categoryid' => 'required|not_in:Elegir'
        ];

        $messages = [
            'name.required' => 'El nombre de la subcategoria es requerido',
            'name.unique' => 'Ya existe una subcategoria con ese nombre',
            'name.min' => 'El nombre deve tener al menos 3 caracteres',
            'categoryid.not_in' => 'Selecciona una categoria diferente de Elegir',
        ];

        $this->validate($rules,$messages);

        $category = SubCategory::create([
            'name' => $this->name,
            'category_id' => $this->categoryid
        ]);

        $customFileName;
        if ($this->image) {
            $customFileName = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('public/subcategories', $customFileName);
            $category->image = $customFileName;
            $category->save();
        }

        $this->resetUI();
        $this->emit('subcategory-added','Sub Categoria Registrada');
    }

    public function Edit(SubCategory $subcategory){
        $this->name = $subcategory->name;
        $this->selected_id = $subcategory->id;
        $this->categoryid = $subcategory->category_id;
        $this->image = null;

        $this->emit('show-modal', 'show modal!');
    }

    public function resetUI(){
        $this->name = '';
        $this->image = null;
        $this->search = '';
        $this->categoryid = 'Elegir';
        $this->selected_id = 0;
    }

    public function Update(){
        $rules = [
            'name' => "required|min:3|unique:sub_categories,name,{$this->selected_id}",
            'categoryid' => 'required|not_in:Elegir'
        ];

        $messages = [
            'name.required' => 'Nombre de la subcategoria es requerido',
            'name.min' => 'la subcategoria deve tener al menos tres caracteres',
            'name.unique' => 'El nombre de esta subcategoria ya existe',
            'categoryid.not_in' => 'Selecciona una categoria diferente de Elegir',
        ];

        $this->validate($rules,$messages);

        $category = SubCategory::find($this->selected_id);
        $category->update([
            'name' => $this->name,
            'category_id' => $this->categoryid
        ]);

        if ($this->image) {
            $customFileName = uniqid() . '_.' .$this->image->extension();
            $this->image->storeAs('public/subcategories', $customFileName);
            $imageName = $category->image;
            $category->image = $customFileName;
            $category->save();
            
            if ($imageName != null) {
                if (file_exists('storage/subcategories/' . $imageName)) {
                    unlink('storage/subcategories/' . $imageName);
                }
            }
        }

        $this->resetUI();
        $this->emit('subcategory-updated','Sub Categoria Actualizada');
    }
    
    protected $listeners = [
        'deleteRow' => 'Destroy'
    ];

    public function Destroy(SubCategory $subcategory){
        $imageName = $subcategory->image;
        $subcategory->update([
            'image' => null
        ]);
        $subcategory->delete();

        if ($imageName != null) {
            unlink('storage/subcategories/' . $imageName);
        }

        $this->resetUI();
        $this->emit('subcategory-deleted','Sub Categoria Eliminada');
    }
}
