@can('goodissuedatereceipt')
    @if ($autorizaoutputdate != null)
        <a href="" data-target="#modal-goodissuedate-{{$id}}" data-toggle="modal" data-placement="top">
            <button type="button"
                    class="btn waves-effect waves-light btn-outline-info btn-sm"
                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                <img src="{{asset('/ico/uploadcsv.png')}}" alt="" class="img-16">
                <span style="font-size: 11px;"> Cerrar Recibo </span>
            </button>
        </a>
        @include('Receipt.ScheduleReceipt.options.goodissuedate')
    @else
        {{$goodissuedate}}
    @endif
@endcan


