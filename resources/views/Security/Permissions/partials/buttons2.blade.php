@can('securitypermissions.edit')
    <a href="{{url('securitypermissions/' . $id . '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

