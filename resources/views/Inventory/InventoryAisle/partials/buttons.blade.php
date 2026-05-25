@can('inventoryaisles.edit')
    <a href="{{url('inventoryaisles/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

