<div class="btn-group">
    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm  dropdown-toggle"
            data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
        <i class="ti ti-settings"></i>
        Acciones
    </button>
    <div class="dropdown-menu animated flipInX">

        @if ($statusid != 'DT')
            @can('monitoranalistatrafico.edit')
                <a href="{{url('monitoranalistatrafico/' . $id . '/edit')}}" class="dropdown-item">
                    <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16 ml-2">
                    <span style="text-transform: capitalize;font-size: 0.675rem !important;">Registrar eventos facturación</span>
                </a>
            @endcan
        @endif

        @can('monitoroperation.edit')
            @if ($statusid != 'EJ')
                <a href="{{url('monitoroperation/' . $id . '/edit')}}" class="dropdown-item">
                    <img src="{{asset('/ico/time.png')}}" alt="" class="img-20 ml-2 mr-2">
                    <span style="text-transform: capitalize;font-size: 0.675rem !important;">Registrar eventos operativos</span>
                </a>
            @endif
        @endcan

        @can('monitoroperation.check')
            <a href="{{url('monitoroperation/' . $id . '/monitorcheck')}}" class="dropdown-item">
                <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-20 ml-2 mr-2">
                <span style="text-transform: capitalize;font-size: 0.675rem !important;">Monitor de verificación</span>
            </a>
        @endcan
        @can('shipmentstages.index')
            <a href="{{url('shipmentstages/' . $id . '/editstage')}}" class="dropdown-item">
                <img src="{{asset('/ico/inventorylabels.png')}}" alt="" class="img-16 ml-2 mr-2">
                <span style="text-transform: capitalize">Asignar cortina</span>
            </a>
        @endcan
    </div>
</div>
