@can('storageincidencesjourneys.edit')
        <a href="{{url('storageincidencesjourneys/' . $folioid. '/edit')}}" data-toggle="tooltip" data-placement="top"
           title="Modificar incidencia">
            <img src="{{asset('/ico/adedit.png')}}" alt="" class="img-16 ml-2 mr-2">
        </a>
@endcan

@can('storageincidencesjourneys.response')
    @if($statusid == 'AB')
        <a href="{{url('storageincidencesjourneys/' . $folioid . '/viewresponse')}}" data-toggle="tooltip"
           data-placement="top"
           title="Agregar Respuesta">
            <img src="{{asset('/ico/securitypermission.png')}}" alt="" class="img-16 ml-2 mr-2">
        </a>
    @endif
@endcan


@can('storageincidencesjourneys.destroy')
    <a href="javascript:void(0)" class="btn-delete-location" data-id="{{ $folioid }}" data-toggle="tooltip"
       data-placement="top" title="{{ empty($deleted_at) ? 'Eliminar Registro' : 'Restaurar Registro' }}">
        <img src="{{ asset(empty($deleted_at) ? '/ico/addestroy1.png' : '/ico/typemovements.png') }}"
             alt="" class="img-16 ml-2 mr-2">
    </a>
@endcan



