@can('goodissuedatereceipt')
    @if ($statusid == 'TE')
        <a href="" data-target="#modal-goodissuedate-{{$receiptid}}" data-toggle="modal" data-placement="top">
            <button type="button"
                    class="btn waves-effect waves-light btn-outline-info btn-sm btn-block"
                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                <img src="{{asset('/ico/edilogs.png')}}" alt="" class="img-16">
                <span class="text-button"> Registrar PO</span>
            </button>
        </a>
        @include('Receipt.SchedulleV2.partials.goodissuedate')
    @else
        {{$grsap}}
    @endif
@endcan


