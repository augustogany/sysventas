<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <p> {{$componentName}} | {{$pageTitle}}</p>
                </h4>
                <ul class="tabs tab-pills">
                    <li>
                        <a href="javascript:void(0)" class="tabmenu bg-dark" data-toggle="modal" data-target="#theModal">
                            Agregar
                        </a>
                    </li>
                    <li>
                        <a href="{{route('printBarcode',json_encode($selected))}}" class="tabmenu bg-dark" target="_blank">
                            Print Code
                        </a>
                    </li>
                    <li>
                        <a href="{{route('printBarcodeAll') }}" class="tabmenu bg-dark" target="_blank">
                            Print All
                        </a>
                    </li>
                </ul>
            </div>
            @include('common.searchbox')
            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-bordered striped mt-1">
                        <thead class="text-white" style="background: #3B3F5C">
                            <tr>
                                <th></th>
                                <th class="table-th text-white">DESCRIPCION</th>
                                <th class="table-th text-white text-center">BARCODE</th>
                                <th class="table-th text-white text-center">CATEGORIA</th>
                                <th class="table-th text-white text-center">PRECIO</th>
                                <th class="table-th text-white text-center">STOCK</th>
                                <th class="table-th text-white text-center">INV. MIN</th>
                                <th class="table-th text-white text-center">IMAGEN</th>
                                <th class="table-th text-white text-center">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $key => $product)
                                <tr>
                                    <td class="text-center">
                                        <div class="n-check">
                                            <label class="new-control new-checkbox checkbox-primary">
                                                <input type="checkbox"
                                                wire:change="Addproducttoprintcode($('#p'+{{$product->id}}).is(':checked'),'{{$product->barcode}}')"
                                                id="p{{$product->id}}"
                                                value="{{$product->id}}"
                                                class="new-control-input"
                                                >
                                                <span class="new-control-indicator"></span>
                                                <h6></h6>
                                            </label>
                                        </div>
                                    </td>
                                    <td><h6 class="text-left">{{$product->name}}</h6></td>
                                    <td><h6 class="text-center">{{$product->barcode}}</h6></td>
                                    <td><h6 class="text-center">{{$product->category}}</h6></td>
                                    <td><h6 class="text-center">{{$product->price}}</h6></td>
                                    <td><h6 class="text-center">{{$product->stock}}</h6></td>
                                    <td><h6 class="text-center">{{$product->alerts}}</h6></td>
                                    <td class="text-center">
                                        <span>
                                            <img src="{{ asset('storage/products/'.$product->imagen)}}" alt="imagen de ejemplo" height="70" width="80" class="rounded">
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:void(0)" 
                                        wire:click="Edit({{$product->id}})"
                                        class="btn btn-dark mtmobile" 
                                        title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0)" 
                                        onclick="Confirm('{{$product->id}}')"
                                        class="btn btn-dark" 
                                        title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">Sin Datos</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                   {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
    @include('livewire.products.form')
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        window.livewire.on('product-added', msg => {
            $('#theModal').modal('hide')
            noty(msg)
        });

        window.livewire.on('product-updated', msg => {
            $('#theModal').modal('hide')
            noty(msg)
        });

        window.livewire.on('product-deleted', msg => {
            noty(msg)
        });

        window.livewire.on('show-modal', msg => {
            $('#theModal').modal('show')
        });

        window.livewire.on('modal-hide', msg => {
            $('#theModal').modal('hide')
        });

        window.livewire.on('error-printbarcode', Msg => {
            noty(Msg)
        });

        $('#theModal').on('hidden.bs.modal', function(e) {
            $('.er').css('display','none')
        })
    });
    function Confirm(id)
    {
        let me = this
        swal({
            title: 'CONFIRMAR',
            text: '¿DESEAS ELIMINAR EL REGISTRO?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3B3F5C',//#3B3F5C
            cancelButtonColor: '#fff',
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar',
            closeOnConfirm: false
        }).then(function(result) {
            if (result.value) {
                window.livewire.emit('deleteRow',id)
                toastr.success('info', 'Registro eliminado con éxito')
                swal.close()
            }
        })
    }
</script>

