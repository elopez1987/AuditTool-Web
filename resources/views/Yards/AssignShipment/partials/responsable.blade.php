@if(empty($responsable))
    <a href="" data-target="#modal-addresponsible-{{$id}}" data-toggle="modal">
        <button class="btn waves-effect waves-light btn-outline-info btn-sm btn-block">
            <img src="{{asset('/ico/adagregar.png')}}" alt="" style="width: 1.5rem; height: 1.2rem;" class="ml-3">
            <span style="font-size: 0.675rem " class="ml-3"> Agregar</span>
        </button>
    </a>
@else
    <a href="" data-target="#modal-addresponsible-{{$id}}" data-toggle="modal">
            <img src="{{asset('/ico/adreload.png')}}" alt="" style="width: 1.5rem; height: 1.2rem;" class="ml-3 mr-3">
    </a>
    {{$responsable}}
@endif
@include('Yards.AssignShipment.partials.addresponsable')


