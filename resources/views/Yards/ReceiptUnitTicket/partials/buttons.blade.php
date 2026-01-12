
<a href="{{url('downloadticket/' . $folionumber)}}">
    <img src="{{asset('/ico/papeleta.png')}}" alt="" class="img-16 ml-2 mr-2">
</a>

@if($statusid != 'D')

    @can('receiptunitticket.destroy')
        <a href="" data-target="#modal-delete-{{$id}}" data-toggle="modal">
            <img src="{{asset('/ico/destroy2.png')}}" alt="" class="img-16 ml-2 mr-2">
        </a>
        @include('Yards.ReceiptUnitTicket.delete')
    @endcan

    @can('receiptunitticket.edit')
        <a href="" data-target="#modal-updatestatus-{{$id}}" data-toggle="modal">
            <img src="{{asset('/ico/reloadpage.png')}}" alt="" class="img-16 ml-2 mr-2">
        </a>
        @include('Yards.ReceiptUnitTicket.updatestatus')
    @endcan


    @can('receiptunitticket.edit')
        <a href="" data-target="#modal-checkout-{{$id}}" data-toggle="modal">
            <img src="{{asset('/ico/close.png')}}" alt="" class="img-16 ml-2 mr-2">
        </a>
        @include('Yards.ReceiptUnitTicket.checkout')
    @endcan

    @can('receiptunitticket.kgneto')
        <a href="" data-target="#modal-kgneto-{{$id}}" data-toggle="modal">
            <img src="{{asset('/ico/peso.png')}}" alt="" class="img-16 ml-2 mr-2">
        </a>
        @include('Yards.ReceiptUnitTicket.kgneto')
    @endcan
@endif
