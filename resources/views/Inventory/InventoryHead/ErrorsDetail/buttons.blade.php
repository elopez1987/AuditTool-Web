@can('inventoryheads.adjust')
    <a href="" data-target="#modal-activate-{{$id}}" data-toggle="modal" data-placement="top">
        <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                data-toggle="tooltip" data-placement="top" title="Activar Inventario">
            <img src="{{asset('/ico/typemovements.png')}}" alt="" class="img-16">
            <span style="font-size: 11px;"> Ajustar </span>
        </button>
    </a>
    @include('Inventory.InventoryHead.ErrorsDetail.adjust-inventory')
@endcan


