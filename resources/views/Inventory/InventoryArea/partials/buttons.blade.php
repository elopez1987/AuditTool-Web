@can('inventoryareas.edit')
    <a href="{{url('inventoryareas/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

