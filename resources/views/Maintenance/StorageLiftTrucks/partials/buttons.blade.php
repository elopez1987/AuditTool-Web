@can('storagelifttrucks.edit')
    <a href="{{url('storagelifttrucks/' . $serialnumber. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

@can('storagelifttrucks.show')
    <a href="{{url('storagelifttrucks/' . $serialnumber )}}" data-toggle="tooltip" data-placement="top"
       title="Intentos de checklist bloqueados">
        <img src="{{asset('/ico/data_hosting_information_internet_security_server_storage_icon_127051.png')}}" alt=""
             class="img-16 ml-1">
    </a>
@endcan


{{--@can('storagelifttrucks.parameters')--}}
{{--    <a href="{{url('storagelifttrucks/' . $id . '/parameters')}}" data-toggle="tooltip"--}}
{{--       data-placement="top"--}}
{{--       title="Agregar Parametros">--}}
{{--        <img src="{{asset('/ico/systemparameters.png')}}" alt="" class="img-16 ml-1">--}}
{{--    </a>--}}
{{--@endcan--}}

@can('storagelifttrucks.destroy')
    <a href="" data-target="#modal-delete-{{$serialnumber}}"
       data-toggle="modal">
        <img src="{{asset('/ico/destroy2.png')}}" alt=""
             class="img-16 ml-2">
    </a>
    @include('Maintenance.StorageLiftTrucks.delete')
@endcan
