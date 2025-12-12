<div class="btn-group">
    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm dropdown-toggle"
            data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
        <i class="ti ti-settings font-12 mr-2"></i>
      <span style="font-size: 11px">  Acciones </span>
    </button>
    <div class="dropdown-menu animated flipInX">

        @if ($statusid != 'OU')
            @can('receiptunits.edit')
                <a href="{{url('receiptunits/' . $id. '/edit')}}" class="dropdown-item">
                    <img src="{{asset('/ico/edit-validated_40458.png')}}" alt="" class="img-16 ml-2 mr-2">
                    <span >Modificar registro</span>
                </a>
            @endcan
        @endif

        @can('generatelabel')
            @if ($statusid != 'OU')
                <a href="{{url('generatelabel/' . $id)}}" class="dropdown-item">
                    <img src="{{asset('/ico/systemprinter.png')}}" alt="" class="img-16 ml-2 mr-2">
                    <span >Imprimir etiqueta de caja</span>
                </a>
            @endif
        @endcan
        @can('generateinvoicetransport')
            <a href="{{url('generateinvoicetransport/' . $id)}}" class="dropdown-item">
                <img src="{{asset('/ico/invoice.png')}}" alt="" class="img-16 ml-2 mr-2">
                <span  >Generar folio de entrada</span>
            </a>
        @endcan
        @can('generateinterface')
            <a href="{{url('generateinterface/' . $id)}}" class="dropdown-item">
                <img src="{{asset('/ico/time.png')}}" alt="" class="img-16 ml-2 mr-2">
                <span  >Generar Interface a Wms Izel</span>
            </a>
        @endcan

        @can('receiptunits.uploadevidence')
            <a href="{{url('uploadEvidenceProcess/' . $id)}}" class="dropdown-item">
                <img src="{{asset('/ico/evidencein.png')}}" alt="" class="img-16 ml-2 mr-2">
                <span >Subir Evidencia De Proceso</span>
            </a>
        @endcan

        <a href="{{url('receiptunitscomments/' . $id )}}" class="dropdown-item">
            <img src="{{asset('/ico/edilogs.png')}}" alt="" class="img-16 ml-2 mr-2">
            <span >Registrar comentario a unidad</span>
        </a>

        @can('tracereceiptunit.index')
            <a href="{{url('tracereceiptunit/' . $id )}}" class="dropdown-item" target="_blank">
                <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16 ml-2 mr-2">
                <span  >Kardex de unidad</span>
            </a>
        @endcan

        @can('receiptunits.ticket')
            <a href="{{url('downloadticket/' . $id)}}" class="dropdown-item ">
                <img src="{{asset('/ico/papeleta.png')}}" alt="" class="img-16 ml-2 mr-2">
                <span >Descargar Ticket de bascula</span>
            </a>
        @endcan
    </div>
</div>



