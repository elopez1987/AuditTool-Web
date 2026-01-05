<div class="btn-group">
    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm dropdown-toggle"
            data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
        <i class="ti ti-settings"></i>
        <span style="font-size: 11px">  Acciones </span>
    </button>
    <div class="dropdown-menu animated flipInX">

        @can('receiptasn.edit')
            @if ($statusid == 'RE'  || $statusid == 'TA')
                <a href="{{url('receiptasn/' . $id . '/edit')}}" class="dropdown-item">
                    <img src="{{asset('/ico/edit-validated_40458.png')}}" alt="" class="img-16 ml-2 mr-2">
                    <span >Modificar registro</span>
                </a>
            @endif
        @endcan

        @if ($statusid != 'IG')
            @can('receiptasn.checklistant')
                <a href="{{url('receiptasn/' . $id . '/checklist')}}" class="dropdown-item">
                    <img src="{{asset('/ico/checklistin.png')}}" alt="" class="img-16 ml-2 mr-2">
                    <span >Realizar check list de documentos</span>
                </a>
            @endcan
        @endif

        @can('addstock')
            @if ($statusid != 'IG' )
                <a href="{{url('receiptasn/' . $id . '/addstock')}}" class="dropdown-item">
                    <img src="{{asset('/ico/storageareas1.png')}}" alt="" class="img-16 ml-2 mr-2">
                    <span>Registrar solicitud de pedido</span>
                </a>
            @endif
        @endcan

        @can('receiptasn.addpallets')
            <a href="{{url('receiptasn/' . $id . '/addpallets')}}" class="dropdown-item">
                <img src="{{asset('/ico/chep.jpg')}}" alt="" class="img-16 ml-2 mr-2">
                <span
                >Registrar recepción de tarimas</span>
            </a>
        @endcan

        @can('receiptasn.show')
            <a href="{{url('receiptasn/' . $id )}}" class="dropdown-item">
                <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16 ml-2 mr-2">
                <span>Detalle del recibo</span>
            </a>
        @endcan


        @if($statusid == 'TA')
            @can('receiptasn.addstage')
                <a href="{{url('receiptasnstage/' . $id )}}" class="dropdown-item">
                    <img src="{{asset('/ico/import.png')}}" alt="" class="img-16 ml-2 mr-2">
                    <span>Registrar cortina anticipada</span>
                </a>
            @endcan
        @endif


    </div>
</div>
