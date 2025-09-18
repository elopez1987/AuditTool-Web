@can('receiptunitticket.destroy')
    <a href="" data-target="#modal-delete-{{$id}}"
       data-toggle="modal">
        <img src="{{asset('/ico/destroy2.png')}}" alt="" class="img-16 ml-2">
    </a>
    @include('Yards.ReceiptUnitTicket.delete')
@endcan