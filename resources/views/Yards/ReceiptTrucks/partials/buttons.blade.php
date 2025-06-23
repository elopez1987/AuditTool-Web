@can('receipttrucks.edit')
    <a href="{{url('receipttrucks/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

@can('registerOutPutTruck')
        <a href="" data-target="#modal-registeroutputunit-{{$id}}" data-toggle="modal" >
            <img src="{{asset('/ico/typemovements.png')}}" alt="" class="img-16 ml-2">
        </a>
        @include('Yards.ReceiptTrucks.registeroutputunit')
@endcan

@can('generateinvoicetransporttruck')
    <a href="{{url('generateinvoicetransporttruck/' . $id)}}" data-toggle="tooltip" data-placement="top"
       title="Imprimir PDF">
        <img src="{{asset('/ico/invoice.png')}}" alt="" class="img-16 ml-2">
    </a>
@endcan

