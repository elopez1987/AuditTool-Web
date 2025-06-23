@can('securitypermissions.edit')
    <a href="{{url('securitypermissions/' . $id . '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan
@can('securitypermissions.destroy')
    <div class="btn-group">
        <a href="" data-target="#modal-delete-{{$id}}"
           data-toggle="modal">
            <img src="{{asset('/ico/addestroy.png')}}" alt="" class="img-16">
        </a>
        @include('Security.Permissions.partials.destroy')
    </div>
@endcan
