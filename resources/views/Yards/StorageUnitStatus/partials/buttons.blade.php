@can('storageunitstatus.edit')
    <a href="{{url('storageunitstatus/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

