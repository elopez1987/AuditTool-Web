
@if ($statusid != 'DT')
    @can('monitoranalistatrafico.edit')
        <a href="{{url('monitoranalistatrafico/' . $id . '/edit')}}"
           data-toggle="tooltip" data-placement="top"
           title="Agregar detalle del embarque">
            <img src="{{asset('/ico/viewdetail.png')}}" alt=""
                 class="img-16 ml-2">
        </a>
    @endcan
@endif

@if ($statusid != 'DT')
{{--    @can('datatransport.edit')--}}
{{--        <a href=""--}}
{{--           data-target="#modal-addtransporttoshipment-{{$id}}"--}}
{{--           data-toggle="modal" data-toggle="tooltip"--}}
{{--           data-placement="top" title="Agregar transporte">--}}
{{--            <img src="{{asset('/ico/typetruck.png')}}" alt="" class="img-16 ml-2">--}}
{{--        </a>--}}
{{--        @include('Shipments.OrderMonitor.AnalystTraffic.partials.addtransporttoshipment')--}}
{{--    @endcan--}}

<a href="{{url('datatransport/' . $id . '/edit')}}" data-toggle="tooltip" data-placement="top"
   title="Asignar cortina anticipada">
    <img src="{{asset('/ico/typetruck.png')}}" alt="" class="img-16 ml-2">
</a>
@endif

@can('shipmentstages.index')
    <a href="{{url('shipmentstages/' . $id . '/editstage')}}" data-toggle="tooltip" data-placement="top"
       title="Asignar cortina anticipada">
        <img src="{{asset('/ico/inventorylabels.png')}}" alt="" class="img-16 ml-2">
    </a>
@endcan


