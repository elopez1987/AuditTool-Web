@can('receiptunitticket.destroy')
    <a href="" data-target="#modal-delete-{{$id}}"
       data-toggle="modal">
        <img src="{{asset('/ico/destroy2.png')}}" alt="" class="img-16 ml-2">
    </a>
    @include('Yards.ReceiptUnitTicket.delete')
@endcan

@can('receiptunitticket.edit')
    <a href="" data-target="#modal-updatestatus-{{$id}}"
       data-toggle="modal">
        <img src="{{asset('/ico/reloadpage.png')}}" alt="" class="img-16 ml-2">
    </a>
    @include('Yards.ReceiptUnitTicket.updatestatus')
@endcan