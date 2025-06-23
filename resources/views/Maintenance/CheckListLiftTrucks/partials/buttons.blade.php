@can('checklistlifttrucks.printformat')
    <a href="{{url('printFormatToCheckList/' . $id)}}" data-toggle="tooltip" data-placement="top"
       title="Imprimir formato" target="_blank">
        <img src="{{asset('/ico/pdf.png')}}" alt="" class="img-16 ml-2 mr-2">
    </a>
@endcan


<a href="{{url('checklistlifttrucks/' . $id )}}" data-toggle="tooltip" data-placement="top" title="Detalles del check list">
    <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16 ml-2 mr-2">
</a>


