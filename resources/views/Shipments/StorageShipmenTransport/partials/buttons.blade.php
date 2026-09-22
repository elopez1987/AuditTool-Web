@can('datatransport.addfortransport')
    <a href="{{url('shipment-transport/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan
@can('datatransport.printPDF')
    @if ($statustransport == 1)
        <a href="{{url('printPDF/' . $id)}}" data-toggle="tooltip" data-placement="top"
           title="Imprimir PDF">
            <img src="{{asset('/ico/invoice.png')}}" alt="" class="img-16 ml-2">
        </a>
    @endif
@endcan
