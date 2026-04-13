@can('orderdeliveries.show')
    <a href="{{url('orderdeliveries/' . $id)}}" data-toggle="tooltip" data-placement="top" title="Ver detalle">
        <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16 m-l-2 mr-2">
    </a>
@endcan

@if ($statusid != 'DH')
    @if ($statusid != "CA")
        @can('orderdeliveries.edit')
            <a href="{{url('orderdeliveries/' . $id . '/edit')}}" data-toggle="tooltip" data-placement="top"
               title="Modificar">
                @include('buttons.edit')
            </a>
        @endcan
        @can('orderdeliveries.uploadevidence')
            <a href="{{url('orderdeliveries/' . $token . '/uploadevidence')}}" data-toggle="tooltip"
               data-placement="top"
               title="Modificar">
                <img src="{{asset('/ico/img.png')}}" alt="" class="img-16 ml-2 mr-2">
            </a>
        @endcan
    @endif
@endif

@if ($statusid != "CA")
    @can('orderdeliveries.uploadfile')
        @if ($evidencefile == null)
            <a href="" data-target="#modal-uploadfile-{{$id}}" data-toggle="modal">
                <img src="{{asset('/ico/upload.png')}}" alt="" class="img-16 ml-2 mr-2">
            </a>
            @include('Shipments.OrderDeliveries.partials.uploadfile')
        @endif
    @endcan
@endif


@can('monitoroperation.check')
    <a href="{{url('monitoroperation/' . $id . '/monitorcheck')}}" class="m-l-2" data-toggle="tooltip"
       data-placement="top" title="Detalle de verificación">
        <img src="{{asset('/ico/tablesview.png')}}" alt="" class="img-16 ml-2 mr-2">
    </a>
@endcan

@if($statusid == 'DH')
    @can('orderdeliveries.adddeliveries')
        <a href="{{url('orderdeliveries/' . $id . '/adddeliveries')}}" data-toggle="tooltip" data-placement="top"
           title="Agregar facturas">
            <img src="{{asset('/ico/edilogs.png')}}" alt="" class="img-16 ml-2 mr-2">
        </a>
    @endcan
@endif
