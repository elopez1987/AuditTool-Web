@can('inventorybox.edit')
    <a href="{{url('inventorybox/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Inventario">
        <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16 ml-1">
    </a>

    @if ($statusid != 'TO')
        <a onclick="activeInventory({!! $id !!})" class="ml-2">
            <img src="{{asset('/ico/typemovements.png')}}" alt="" class="img-16 ml-1">
        </a>
    @endif

@endcan

@can('inventorybox.destroy')
    @if ($statusid != 'TO')
        <a href="" data-target="#modal-destroy-{{$id}}" data-toggle="modal" data-toggle="tooltip"
           data-placement="top" title="Eliminar Inventario" class="ml-2">
            <img src="{{asset('/ico/destroy2.png')}}" alt="" class="img-16">
        </a>
        @include('Yards.InventoryBoxes.destroyInventory')
    @endif
@endcan


