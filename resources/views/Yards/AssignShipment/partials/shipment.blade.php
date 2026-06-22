@if ($shipmentid == '0' || $shipmentid == '00' || $shipmentid == '000' || $shipmentid == '0000' || empty($shipmentid))
    <a href="" data-target="#modal-addshipmenttoreceipt-{{$id}}" data-toggle="modal" >
        <button  class="btn waves-effect waves-light btn-outline-info btn-sm btn-block" >
            <img src="{{asset('/ico/adagregar.png')}}" alt="" style="width: 1.5rem; height: 1.2rem;" class="ml-3">
            <span style="font-size: 0.675rem " class="ml-3"> Agregar</span>
        </button>
    </a>
    @include('Yards.AssignShipment.partials.addshipmenttoreceiptunit')
@else
    {{$shipmentid}}
@endif


