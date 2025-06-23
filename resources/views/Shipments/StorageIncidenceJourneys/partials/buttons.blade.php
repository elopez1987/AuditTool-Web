@can('storageincidencesjourneys.edit')
        <a href="{{url('storageincidencesjourneys/' . $folioid. '/edit')}}" data-toggle="tooltip" data-placement="top"
           title="Modificar incidencia">
            <img src="{{asset('/ico/adedit.png')}}" alt="" class="img-16 ml-1">
        </a>
@endcan

@can('storageincidencesjourneys.response')
    @if($statusid == 'AB')
        <a href="{{url('storageincidencesjourneys/' . $folioid . '/viewresponse')}}" data-toggle="tooltip"
           data-placement="top"
           title="Agregar Respuesta">
            <img src="{{asset('/ico/securitypermission.png')}}" alt="" class="img-16 ml-2">
        </a>
    @endif
@endcan
