@if($statusid == 'E')
    @can('storageevidenceprocess.edit')
        <a href="{{url('storageevidenceprocess/' . $recordid. '/edit')}}" data-toggle="tooltip" data-placement="top"
           title="Modificar Registro">
            @include('buttons.edit')
        </a>
    @endcan
@endif

{{--@if($statusid == 'E')--}}
{{--    @can('storageevidenceprocess.uploadevidenceshipment')--}}
{{--        <a href="{{url('storageevidenceprocess/' . $recordid . '/uploadevidenceshipment')}}" data-toggle="tooltip"--}}
{{--           data-placement="top" title="Subir evidencia">--}}
{{--            <img src="{{asset('/ico/evidence.png')}}" alt="" class="img-16 ml-1">--}}
{{--        </a>--}}
{{--    @endcan--}}
{{--@endif--}}

@if($statusid == 'D')
    @can('storageevidenceprocess.generatePDF')
        <a href="{{url('generatePDF/' . $recordid )}}" data-toggle="tooltip"
           data-placement="top" title="Descargar PDF">
            <img src="{{asset('/ico/pdf.png')}}" alt="" class="img-16 ml-1">
        </a>
    @endcan
@endif

@if($statusid == 'D')
    @can('storageevidenceprocess.generatemapload')
        <a href="{{url('generatemapload/' . $recordid )}}" data-toggle="tooltip"
           data-placement="top" title="Descargar mapa de carga">
            <img src="{{asset('/ico/papeleta.png')}}" alt="" class="img-16 ml-1">
        </a>
    @endcan
@endif


<a href="{{url('storageevidenceprocess/' . $recordid )}}" data-toggle="tooltip"
   data-placement="top" title="Ver evidencias">
    <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16 ml-1">
</a>




