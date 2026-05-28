@can('inventoryheads.edit')
    <a href="{{url('inventoryheads/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

@can('inventoryheads.show')
    <a href="{{url('inventoryheads/' . $id )}}" data-toggle="tooltip" data-placement="top"
       title="Detalle del Inventario">
        <img src="{{asset('/ico/dashboard.png')}}" alt="" class="img-16 ml-2 mr-2">
    </a>
@endcan

