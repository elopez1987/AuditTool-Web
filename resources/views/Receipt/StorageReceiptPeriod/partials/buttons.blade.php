@can('storagereceiptperiods.show')
    <a href="{{url('storagereceiptperiods/' . $id )}}">
        <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16 ml-2 mr-2">
    </a>
@endcan
@can('storagereceiptperiods.activate')
    <a href="" data-target="#modal-active-{{$id}}" data-toggle="modal">
        <img src="{{asset('/ico/typemovements.png')}}" alt=""
             class="img-16 ml-2">
    </a>
    @include('Receipt.StorageReceiptPeriod.partials.active')
@endcan
