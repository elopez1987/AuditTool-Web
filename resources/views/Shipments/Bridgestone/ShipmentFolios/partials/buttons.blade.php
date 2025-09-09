@can('storageshipmentfoliospdf')
    @if($statuskeyid == 'FIR-02')
        <a href="{{url('storageshipmentfoliospdf/' . $id )}}" data-toggle="tooltip" data-placement="top"
           title="Exportar Registros">
            <img src="{{asset('/ico/pdf.png')}}" alt="" class="img-16 ml-2 mr-2">
        </a>
    @endif
@endcan
