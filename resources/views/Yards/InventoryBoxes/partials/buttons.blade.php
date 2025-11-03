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
        <a href="javascript:void(0)" class="btn-delete-inventory" data-id="{{$id}}" data-toggle="tooltip"
           data-placement="top" title="Eliminar Registro">
            <img src="{{asset('/ico/addestroy1.png')}}" alt="" class="img-16 ml-2">
        </a>
    @endif
@endcan


