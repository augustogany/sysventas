<div class="connect-sorting">
    <div class="connect-sorting-content">
        <div class="card simple-title-task ui-sortable-handle">
            <div class="card-body">
                @if ($total > 0)
                    <div class="table-responsive tblscroll" style="max-height: 650px; overflow: hidden">
                        <table class="table-bordered table-striped mt-1">
                            <thead class="text-withe" style="background: #3B3F5C">
                                <tr>
                                    <th width="10%"></th>
                                    <th class="table-th text-center text-withe">DESCRIPCION</th>
                                    <th class="table-th text-center text-withe">PRECIO</th>
                                    <th width="13%" class="table-th text-center text-withe">CANT</th>
                                    <th class="table-th text-center text-withe">IMPORTE</th>
                                    <th class="table-th text-center text-withe">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cart as $item)
                                    <tr>
                                        <td class="text-center table-th">
                                            @if (count($item->attributes) > 0)
                                                <span>
                                                    <img 
                                                    src="{{ asset('storage/products/' .$item->attributes[0])}}" 
                                                    alt="imagen"
                                                    height="90"
                                                    width="90"
                                                    class="rounded">
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="n-check" style="padding-left: 10%">
                                                <label class="new-control new-checkbox checkbox-primary">
                                                    <input type="checkbox"
                                                    wire:change="xMayor($('#p'+{{$item->id}}).is(':checked'),$('#p' + {{$item->id}}).val())"
                                                    id="p{{$item->id}}"
                                                    value="{{$item->id}}"
                                                    class="new-control-input"
                                                    >
                                                    <span class="new-control-indicator"></span>
                                                    <h6>{{substr($item->attributes[1],'-3').' '.$item->name}}</h6>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="text-center"><h6>Bs{{number_format($item->price,2)}}</h6></td>
                                        <td>
                                            <input 
                                            type="number" 
                                            id="r{{$item->id}}"
                                            wire:change="updateQty({{$item->id}}, $('#r' + {{$item->id}}).val() )"
                                            style="font-size: 1rem!important"
                                            class="form-control text-center"
                                            value="{{$item->quantity}}">
                                        </td>
                                        <td class="text-center">
                                            <h6>
                                                Bs{{number_format($item->price * $item->quantity,2)}}
                                            </h6>
                                        </td>
                                        <td class="text-center">
                                            <button onclick="Confirm('{{$item->id}}', 'removeItem','Confirmas Eliminar el Registro?')" class="btn btn-dark mbmobile">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <button wire:click.prevent="decreaseQty({{$item->id}})" class="btn btn-dark mbmobile">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <button wire:click.prevent="increaseQty({{$item->id}})" class="btn btn-dark mbmobile">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                <h5 class="text-center text-muted">Agrega productos a la venta</h5>
                @endif
                <div wire:loading.inline wire:target="saveSale">
                    <h4 class="text-danger text-center">Guardando Venta....</h4>
                </div>
            </div>
        </div>
    </div>
</div>