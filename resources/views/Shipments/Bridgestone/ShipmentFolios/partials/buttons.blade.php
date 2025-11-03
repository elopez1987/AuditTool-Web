@can('storageshipmentfoliospdf')
        <a href="{{url('storageshipmentfoliospdf/' . $id )}}" data-toggle="tooltip" data-placement="top"
           title="Exportar Registros" target="_blank">
            <img src="{{asset('/ico/pdf.png')}}" alt="" class="img-16 ml-2 mr-2">
        </a>
@endcan

@can('storageshipmentfolios.show')
    <a href="{{url('storageshipmentfolios/' . $id )}}" data-toggle="tooltip" data-placement="top"
       title="Detalle de Registro" >
        <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16 ml-2 mr-2">
    </a>
@endcan