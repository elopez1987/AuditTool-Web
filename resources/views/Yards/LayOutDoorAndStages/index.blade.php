@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-6" style="height: 700px;overflow-y: scroll;">
            <div class="card">
                <div class="card-header">
                    Cortinas -- <span class="badge badge-danger font-12">Embarque</span> -- <span class="badge badge-success font-12">Descarga</span>
                </div>
                <div class="card-body">
                    <table class="table table-condensed table-hover table-bordered tablegeneral">
                        <thead style="position: sticky;top: 0;">
                            <tr>
                                <th style="width: 20%;font-size: 14px !important;">Cortina</th>
                                <th style="height: 30px !important;font-size: 14px !important;">Linea</th>
                                <th style="height: 30px !important;font-size: 14px !important;">Embarque</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($locations as $location)
                            @if($location->zoneid == 'CO')
                                <tr>
                                    <td class="td-events" style="height: 30px !important;font-size: 14px !important;font-weight: bold;">{{$location->name}}</td>
                                    <td class="td-events" style="height: 30px !important;font-size: 14px !important;">
                                        @foreach($units as $unit)
                                            @if ($unit->locationid == $location->id)
                                                {{$unit->carrier->name}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="td-events" style="height: 30px !important;font-size: 14px !important;">
                                        @foreach($units as $unit)
                                            @if ($unit->locationid == $location->id)
                                                <span
                                                        style="font-size: 14px !important;"
                                                    class="badge {{$unit->razonstatusid == 1 ? 'badge-danger' : 'badge-success' }} font-12"
                                                    data-html="true"
                                                    data-toggle="tooltip"
                                                    data-placement="bottom"
                                                    title="
                                                                Folio: {{$unit->id}}   <br/>
                                                                Placa: {{$unit->boxnumber}}  <br/>
                                                                Linea de Transporte: {{$unit->carrier->name}} <br/>
                                                                Operador: 	{{$unit->operatorname}} <br/>
                                                                F.Enrampe: {{$unit->ensnare}}  <br/>
                                                    "
                                                >
                                                  {{$unit->shipmentid}}
                                                </span>
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6" style="height: 700px;overflow-y: scroll;">
            <div class="card">
                <div class="card-header">
                    Precargas
                </div>
                <div class="card-body">
                    <table class="table table-condensed table-hover table-bordered tablegeneral">
                        <thead style="position: sticky;top: 0;">
                            <tr>
                                <th style="width: 20%;font-size: 14px !important;">Precargas</th>
                                <th style="height: 30px !important;font-size: 14px !important;">Embarque</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($locations as $location)
                            @if($location->zoneid == 'ST')
                                <tr>
                                    <td class="td-events" style="height: 30px !important;font-size: 14px !important;">{{$location->name}}</td>
                                    <td class="td-events" style="height: 30px !important;font-size: 14px !important;">
                                        @foreach($shipments as $shipment)
                                            @if ($shipment->locationid == $location->id)
                                                <a href="{{url('monitoroperation/' . $shipment->id . '/edit')}}">
                                                <span class="badge badge-success font-12" style="font-size: 14px !important;">
                                                {{$shipment->shipmentid}}
                                                </span>
                                                </a>
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        var oTable =   $(".tablegeneral").DataTable({
            "paging"      : true,
            "searching"  : true,
            "ordering"    : true,
            "serverSide" :false,
            'colReorder': true,
            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            // "dom": '<"row"<"col-md-4"l><"col-md-4 text-center"B><"col-md-4"f>>rt<"bottom"p><"clear">',
            "lengthMenu": [[100, 150, 200, 250, -1], [100, 150, 200, 250, "Todos"]],

            buttons: [
                {
                    "extend": "colvis",
                    "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt=''><span  style='font-size: 0.675rem !important;'> Mostar/Ocultar</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
                    columns: ':not(:first):not(:last)'
                },
                {
                    "extend": "csv",
                    "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16'><span  style='font-size: 0.675rem !important;'> Csv</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
                },
                {
                    "extend": "excel",
                    "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt=''><span  style='font-size: 0.675rem !important;'> Excel</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
                }
            ],


            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
        });
    </script>


@endsection
