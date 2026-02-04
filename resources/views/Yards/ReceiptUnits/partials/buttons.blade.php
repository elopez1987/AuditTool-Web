<div class="btn-group dropup">
    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm dropdown-toggle"
            data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
        <i class="ti ti-settings font-12 mr-2"></i>
       Acciones
    </button>
    <div class="dropdown-menu animated flipInX" style="z-index: 9999;">
        @if ($statusid != 'OU')
            @can('receiptunits.edit')
                <a href="{{url('receiptunits/' . $id. '/edit')}}" class="dropdown-item">
                    <img src="{{asset('/ico/edit-validated_40458.png')}}" alt="" class="img-16 ml-2 mr-2">
                    <span style="text-transform: capitalize;">Modificar registro</span>
                </a>
            @endcan
        @endif
        @can('generatelabel')
            @if ($statusid != 'OU')
                <a href="{{url('generatelabel/' . $id)}}" class="dropdown-item">
                    <img src="{{asset('/ico/systemprinter.png')}}" alt="" class="img-16 ml-2 mr-2">
                    <span style="text-transform: capitalize;">Imprimir etiqueta de caja</span>
                </a>
            @endif
        @endcan
        @can('generateinvoicetransport')
            <a href="{{url('generateinvoicetransport/' . $id)}}" class="dropdown-item">
                <img src="{{asset('/ico/invoice.png')}}" alt="" class="img-16 ml-2 mr-2">
                <span  style="text-transform: capitalize;">Generar folio de entrada</span>
            </a>
        @endcan
        @can('generateinterface')
            <a href="{{url('generateinterface/' . $id)}}" class="dropdown-item">
                <img src="{{asset('/ico/time.png')}}" alt="" class="img-16 ml-2 mr-2">
                <span  style="text-transform: capitalize;">Generar Interface a Wms Izel</span>
            </a>
        @endcan
        @can('receiptunits.uploadevidence')
            <a href="{{url('uploadEvidenceProcess/' . $id)}}" class="dropdown-item">
                <img src="{{asset('/ico/evidencein.png')}}" alt="" class="img-16 ml-2 mr-2">
                <span style="text-transform: capitalize;">Subir Evidencia De Proceso</span>
            </a>
        @endcan
        <a href="{{url('receiptunitscomments/' . $id )}}" class="dropdown-item">
            <img src="{{asset('/ico/edilogs.png')}}" alt="" class="img-16 ml-2 mr-2">
            <span style="text-transform: capitalize;">Registrar comentario a unidad</span>
        </a>
        @can('tracereceiptunit.index')
            <a href="{{url('tracereceiptunit/' . $id )}}" class="dropdown-item" target="_blank">
                <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16 ml-2 mr-2">
                <span style="text-transform: capitalize;" >Kardex de unidad</span>
            </a>
        @endcan
        @can('receiptunits.ticket')
            <a href="{{url('downloadticket/' . $id)}}" class="dropdown-item ">
                <img src="{{asset('/ico/papeleta.png')}}" alt="" class="img-16 ml-2 mr-2">
                <span style="text-transform: capitalize;">Descargar Ticket de bascula</span>
            </a>
        @endcan
    </div>
</div>



