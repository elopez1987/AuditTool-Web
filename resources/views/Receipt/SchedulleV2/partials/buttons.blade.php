@can('receiptasn.create')
    @if($shipmentid == null && $statusid != 'DPR-04')
        <a href="" data-target="#modal-create-{{$id}}" data-toggle="modal">
            <img src="{{asset('/ico/addshipment.png')}}" alt="" class="img-16 text-center">
        </a>
        @include('Receipt.SchedulleV2.partials.create')
    @endif
@endcan

@can('receiptasn.show')
    @if($shipmentid !== null)
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

@can('receiptasn.blocked')

    {{--    @if($statusid == 'DPR-04' && $value01 == Auth::id())--}}
    @if($statusid == 'DPR-04')
        <a href="" data-target="#modal-clear-{{$id}}" data-toggle="modal" data-toggle="tooltip"
           data-placement="top" title="liberar registro.">
            <img src="{{asset('/ico/reloadpage.png')}}" alt="" class="img-16 text-center">
        </a>
        @include('Receipt.SchedulleV2.clear')
    @endif

    @if($shipmentid == null && $statusid != 'DPR-04')
        <a href="" data-target="#modal-blocked-{{$id}}" data-toggle="modal" data-toggle="tooltip"
           data-placement="top" title="Bloquear registro.">
            <img src="{{asset('/ico/internet_lock_locked_padlock_password_secure_security_icon_127078.png')}}" alt=""
                 class="img-16 text-center">
        </a>
        @include('Receipt.SchedulleV2.blocked')
    @endif

@endcan
