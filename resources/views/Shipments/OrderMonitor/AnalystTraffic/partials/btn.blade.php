@if ($statusid != 'DT')
    @can('monitoranalistatrafico.edit')
        <a href="{{url('monitoranalistatrafico/' . $id . '/edit')}}"
           data-toggle="tooltip" data-placement="top"
           title="Agregar detalle del embarque">
            <img src="{{asset('/ico/viewdetail.png')}}" alt=""
                 class="img-16 ml-2 mr-2">
        </a>
    @endcan

    <a href="{{url('datatransport/' . $id . '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Asignar transporte">
        <img src="{{asset('/ico/typetruck.png')}}" alt="" class="img-16 ml-2 mr-2">
    </a>
@endif


@can('shipmentstages.index')
    <a href="{{url('shipmentstages/' . $id . '/editstage')}}" data-toggle="tooltip" data-placement="top"
       title="Asignar cortina anticipada">
        <img src="{{asset('/ico/inventorylabels.png')}}" alt="" class="img-16 ml-2">
    </a>
@endcan

