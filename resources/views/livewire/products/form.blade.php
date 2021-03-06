@include('common.modalHeader')
<div class="row">
    <div class="col-sm-12 col-md-8">
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" wire:model.lazy="name" class="form-control product-name" placeholder="Ej: Cursos">
            @error('name')
                <span class="text-danger er">{{ $message}}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Modelo</label>
            <input type="text" wire:model.lazy="model" class="form-control" placeholder="Ej: win">
            @error('model')
                <span class="text-danger er">{{ $message}}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-3">
        <div class="form-group">
            <label>Costo</label>
            <input type="text" data-type="currency" wire:model.lazy="cost" class="form-control" placeholder="Ej: 0.00">
            @error('cost')
                <span class="text-danger er">{{ $message}}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-2">
        <div class="form-group">
            <label>Precio</label>
            <input type="text" data-type="currency" wire:model.lazy="price" class="form-control" placeholder="Ej: 0.00">
            @error('price')
                <span class="text-danger er">{{ $message}}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-2">
        <div class="form-group">
            <label>Precio/Mayor</label>
            <input type="text" data-type="currency" wire:model.lazy="price2" class="form-control" placeholder="Ej: 0.00">
            @error('price2')
                <span class="text-danger er">{{ $message}}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-2">
        <div class="form-group">
            <label>Stock</label>
            <input type="number" wire:model.lazy="stock" class="form-control" placeholder="Ej: 0">
            @error('stock')
                <span class="text-danger er">{{ $message}}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-3">
        <div class="form-group">
            <label>Alertas/Minima</label>
            <input type="number" wire:model.lazy="alerts" class="form-control" placeholder="Ej: 10">
            @error('alerts')
                <span class="text-danger er">{{ $message}}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Categoria</label>
            <select wire:model="categoryid" class="form-control">
                <option value="Elegir" disabled>Elegir</option>
                @foreach ($categories as $category)
                    <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
            </select>
            @error('categoryid')
                <span class="text-danger er">{{ $message}}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Sub Categoria</label>
            <select wire:model="subcategoryid" class="form-control">
                <option value="" disabled>Elegir</option>
                @foreach ($subcategories as $subcategory)
                    <option value="{{$subcategory->id}}">{{$subcategory->name}}</option>
                @endforeach
            </select>
            @error('subcategoryid')
                <span class="text-danger er">{{ $message}}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-8">
        <div class="form-group custom-file">
            <input 
            type="file" 
            wire:model="image" 
            class="custom-file-input form-control" 
            accept="image/x-png, image/gif, image/jpeg, image/jpg">
            <label class="custom-file-label">Imagen {{$image}}</label>
            @error('image')
                <span class="text-danger er">{{ $message}}</span>
            @enderror
        </div>
    </div>
</div>
@include('common.modalFooter')