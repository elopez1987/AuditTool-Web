@if($type == 'IZEL' && $code == '-1')
    @can('reprocessizel')
        <a href="" data-target="#modal-reprocessizel-{{$id}}" data-toggle="modal" data-toggle="tooltip"
           data-placement="top" title="Reprocesar interface a izel">
            <img src="{{asset('/ico/reprocessizel.png')}}" alt="" class="img-16">
        </a>
        @include('Configuration.LogWebService.partials.reprocessizel')
    @endcan
@endif

@if($type == 'RCONTROL')
    @can('reprocessrcontrol')
        <a href="" data-target="#modal-reprocessrcontrol-{{$id}}" data-toggle="modal" data-toggle="tooltip"
           data-placement="top" title="Reprocesar interface a RControl">
            <img src="{{asset('/ico/typemovements.png')}}" alt="" class="img-16 ml-2">
        </a>
        @include('Configuration.LogWebService.partials.reprocessrcontrol')
    @endcan
@endif
