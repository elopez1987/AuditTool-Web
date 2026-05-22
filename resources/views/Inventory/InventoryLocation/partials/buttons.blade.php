@can('inventorycards.edit')
    <a href="{{url('inventorycards/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan



