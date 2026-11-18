<div class="btn-group">
    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm dropdown-toggle"
            data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
        <i class="ti ti-settings"></i>
        <span>  Acciones </span>
    </button>
    <div class="dropdown-menu animated flipInX">

        @can('receiptasn.edit')
            @if ($statusid == 'PG')
                <a href="{{url('receiptasn/' . $uuid_asn . '/edit')}}" class="dropdown-item">
                    <img src="{{asset('/ico/edit-validated_40458.png')}}" alt="" class="img-16 ml-2 mr-2">
                    <span class="text-capitalize">Modificar registro</span>
                </a>
            @endif
        @endcan

        @if ($statusid != 'TR')
            @can('receiptasn.checklistant')
                <a href="{{url('receiptasn/' . $uuid_asn . '/checklist')}}" class="dropdown-item">
                    <img src="{{asset('/ico/checklistin.png')}}" alt="" class="img-16 ml-2 mr-2">
                    <span class="text-capitalize">Realizar check list de documentos</span>
                </a>
            @endcan
        @endif

        @can('addstock')
            @if ($statusid != 'TR' )
                <a href="{{url('receiptasn/' . $uuid_asn . '/addstock')}}" class="dropdown-item">
                    <img src="{{asset('/ico/storageareas1.png')}}" alt="" class="img-16 ml-2 mr-2">
                    <span class="text-capitalize">Registrar solicitud de pedido</span>
                </a>
            @endif
        @endcan


        @can('receiptasn.show')
            <a href="{{url('receiptasn/' . $uuid_asn )}}" class="dropdown-item">
                <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16 ml-2 mr-2">
                <span class="text-capitalize">Detalle del recibo</span>
            </a>
        @endcan


    </div>
</div>
