@can('addpriority')
    @if($shipmentid != null )
        @if ($downloadpriority == '')
            @if($statusid != 'IG')
                <a href="" data-target="#modal-addpriority-{{$receiptid}}" data-toggle="modal" data-toggle="tooltip"
                   data-placement="top" title="Marcar como prioridad">
                    <img src="{{asset('/ico/engine_gear_internet_option_security_setting_web_icon_127066.png')}}" alt=""
                         class="img-16 text-center">
                </a>
                @include('Receipt.SchedulleV2.partials.addpriority')
            @endif
        @else
            {{--            <label class="label label-danger font-10 " style="color: white !important;width: 100%;display: block;text-align: center !important;">Prioridad</label>--}}
            <div class="etiqueta-flecha">
                <span class="etiqueta-texto">Prioridad Alta</span>
            </div>
        @endif
    @endif
@endcan
