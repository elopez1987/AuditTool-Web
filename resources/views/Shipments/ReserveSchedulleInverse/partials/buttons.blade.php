{{--@can('storagecategoryincidences.edit')--}}
{{--    <a href="{{url('storagecategoryincidences/' . $categoryincid. '/edit')}}" data-toggle="tooltip" data-placement="top"--}}
{{--       title="Modificar Registro">--}}
{{--        @include('buttons.edit')--}}
{{--    </a>--}}
{{--@endcan--}}

@can('reserveschedulleinverse.show')
    <a href="{{url('reserveschedulleinverse/' . $id)}}" data-toggle="tooltip" data-placement="top"
       title="Detalle">
        <img src="{{asset('/ico/checklistin.png')}}" alt="" class="img-16 ml-1">
    </a>
@endcan

@can('reserveschedulleinverse.printformat')
    <a href="{{url('schedulleinverseprinformat/' . $id)}}" data-toggle="tooltip" data-placement="top"
       title="Imprimir formato">
        <img src="{{asset('/ico/invoice.png')}}" alt="" class="img-16 ml-1">
    </a>
@endcan
