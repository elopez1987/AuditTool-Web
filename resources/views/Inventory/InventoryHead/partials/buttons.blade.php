@can('inventoryheads.edit')
    <a href="{{url('inventoryheads/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

