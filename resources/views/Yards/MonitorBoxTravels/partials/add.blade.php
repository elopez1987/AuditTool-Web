@if(empty($foliowarehouse))
    @can('monitorcircuitplant.addfolio')
        <a href="" data-target="#modal-addfolio-{{$folioplant}}" data-toggle="modal">
            <img src="{{asset('/ico/storageareas.png')}}" alt="" class="img-16 ml-2">
        </a>
        @include('Yards.MonitorBoxTravels.partials.addfolio')
    @endcan
@else
    {{$foliowarehouse}}
@endif
