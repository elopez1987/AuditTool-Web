@can('gps-devices.edit')
    <a href="{{url('gps-devices/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

{{--@can('shipmenttrucks.destroy')--}}
{{--    <a href="" data-target="#modal-delete-{{$id}}"--}}
{{--       data-toggle="modal">--}}
{{--        <img src="{{asset('/ico/addestroy1.png')}}" alt=""--}}
{{--             class="img-16 ml-2 mr-2">--}}
{{--    </a>--}}
{{--    @include('Yards.ShipmentTrucks.delete')--}}
{{--@endcan--}}

