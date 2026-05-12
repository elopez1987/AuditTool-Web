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

{{--    Agregar validacion que solo se pueda cancelar los programados y solo lo puede cancelar el mismo usuario que creo el registro--}}
@can('receiptasn.cancel-v2')
    @if($shipmentid !== null &&  $value01 == \Illuminate\Support\Facades\Auth::id() && $statusidasn == 'PG')
        <a href="" data-target="#modal-cancel-{{$id}}" data-toggle="modal" data-toggle="tooltip"
           data-placement="top" title="Cancelar registro.">
            <img src="{{asset('/ico/4115230-cancel-close-cross-delete_114048.png')}}" alt="" class="img-16 text-center">
        </a>
        @include('Receipt.SchedulleV2.cancelReceipt')
    @endif
@endcan


@if($statusidasn === 'PG')
    @can('receiptasn.move')
    <a href="" data-target="#modal-move-{{$id}}" data-toggle="modal" data-toggle="tooltip"
       data-placement="top" title="Reprogramar descarga.">
        <img src="{{asset('/ico/reloadpage.png')}}" alt="" class="img-16 text-center">
    </a>
    @include('Receipt.SchedulleV2.move')
    @endcan
@endif


@can('receiptasn.blocked')
    {{--    @if($statusid == 'DPR-04' && $value01 == Auth::id())--}}
    @if($statusid == 'DPR-04')
        <a href="" data-target="#modal-clear-{{$id}}" data-toggle="modal" data-toggle="tooltip"
           data-placement="top" title="Liberar registro.">
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
