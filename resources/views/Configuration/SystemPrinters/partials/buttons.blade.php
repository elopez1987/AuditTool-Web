@can('systemprinters.edit')
    <a href="{{url('systemprinters/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

