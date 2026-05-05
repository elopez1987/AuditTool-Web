@can('storageincidencesjourneys.edit')
    @if($statusid != 'CD')
        <a href="{{url('storageincidencesjourneys/' . $folioid. '/edit')}}" data-toggle="tooltip" data-placement="top"
           title="Modificar incidencia">
            <img src="{{asset('/ico/adedit.png')}}" alt="" class="img-16 ml-1">
        </a>
    @endif
@endcan

@can('storageincidencesjourneys.show')
    <a href="{{url('storageincidencesjourneys/' . $folioid)}}" data-toggle="tooltip" data-placement="top"
       title="Ver detalles">
        <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16 ml-2">
    </a>
@endcan

{{--@can('storageincidencesjourneys.response')--}}
{{--    @if($statusid == 'AB')--}}
{{--        <a href="{{url('storageincidencesjourneys/' . $folioid . '/viewresponse')}}" data-toggle="tooltip"--}}
{{--           data-placement="top"--}}
{{--           title="Agregar Respuesta">--}}
{{--            <img src="{{asset('/ico/securitypermission.png')}}" alt="" class="img-16 ml-2">--}}
{{--        </a>--}}
{{--    @endif--}}
{{--@endcan--}}


@can('storageincidencesjourneys.generatepdf')
{{--    @if($statuspod == 'CON' && $statusid == 'CD')--}}
    @if($statusid == 'CD')
        <a href="{{url('storageincidencesjourneysgeneratepdf/' . $folioid)}}" data-toggle="tooltip"
           data-placement="top"
           title="Imprimir PDF">
            <img src="{{asset('/ico/invoice.png')}}" alt="" class="img-16 ml-2">
        </a>
    @endif
@endcan
