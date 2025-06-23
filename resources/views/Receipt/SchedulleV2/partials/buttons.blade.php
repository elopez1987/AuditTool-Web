@can('receiptasn.create')
    @if($shipmentid == null)
        <a href="{{url('addShipmentToSchedule/' . $id . '/edit')}}">
            <img src="{{asset('/ico/adagregar1.png')}}" alt="" class="img-16 ml-2 mr-2">
        </a>
    @endif
@endcan

@can('receiptasn.show')
    @if($shipmentid != null)
        <a href="{{url('receiptasn/' . $receiptid )}}">
            <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16 ml-2 mr-2">
        </a>
    @endif
@endcan

@if ($statusid == 'IG')
    @can('receiptasn.kardex')
        <a href="{{url('receiptasn/' . $receiptid . '/kardex')}}">
                <img src="{{asset('/ico/pdf.png')}}" alt="" class="img-16 ml-2 mr-2">
        </a>
    @endcan
@endif

