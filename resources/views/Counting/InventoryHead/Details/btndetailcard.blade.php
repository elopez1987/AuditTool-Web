<a href="{{url('detailcard/' . $recordid )}}" data-toggle="tooltip" data-placement="top"
   title="Ver detalle de cedula">
    <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16 ml-1">
</a>

@if(!empty($finishdate01))
    <a href="{{url('printcardcount01/' . $recordid )}}" data-toggle="tooltip" data-placement="top"
       title="Cédula conteo 01">
        <img src="{{asset('/ico/pdf.png')}}" alt="" class="img-16 ml-1">
    </a>
@endif

@if(!empty($finishdate02))
    <a href="{{url('printcardcount02/' . $recordid )}}" data-toggle="tooltip" data-placement="top"
       title="Cédula conteo 02">
        <img src="{{asset('/ico/invoice.png')}}" alt="" class="img-16 ml-1">
    </a>
@endif


<a href="" data-target="#modal-updatestatus-{{$recordid}}" data-toggle="modal">
    <img src="{{asset('/ico/typemovements.png')}}" alt="" class="img-16 ml-2">
</a>
@include('Counting.InventoryHead.Details.updatecardfree')


{{--Visualizar el log de errores por cedula--}}
<a href="{{url('detaillogcard/' . $recordid )}}" data-toggle="tooltip" data-placement="top"
   title="Ver log de cedula">
    <img src="{{asset('/ico/viewdetails.png')}}" alt="" class="img-16 ml-1">
</a>
