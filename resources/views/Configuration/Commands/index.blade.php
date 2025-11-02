@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Comandos del sistema.
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-10">
                            <table id="table-logs"
                                   class="table table-bordered  table-hover table-condensed tablegeneral">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white" style="width: 50%">Evento</th>
                                    <th class="bg-inverse text-white">Ejecutar</th>
                                    <th class="bg-inverse text-white">Centro de distribución</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="font-12"> Limpiar sistama</td>
                                    <td>
                                        <a href="{{url('clearcachesystem')}}">
                                            <img src="{{asset('/ico/systemfunctions.png')}}" alt="" class="img-25 ml-1">
                                        </a>
                                    </td>
                                    <td>General</td>
                                </tr>
                                <tr>
                                    <td class="font-12"> Enviar datos a RControl</td>
                                    <td>
                                        <a href="{{url('senddatatorcontrol')}}">
                                            <img src="{{asset('/ico/systemfunctions.png')}}" alt="" class="img-25 ml-1">
                                        </a>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-12"> Enviar datos a Izel</td>
                                    <td>
                                        <a href="{{url('senddatatoizel')}}">
                                            <img src="{{asset('/ico/systemfunctions.png')}}" alt="" class="img-25 ml-1">
                                        </a>
                                    </td>
                                    <td>General</td>
                                </tr>
                                <tr>
                                    <td class="font-12"> Limpiar tabla de log de webservice</td>
                                    <td>
                                        <a href="{{url('cleartablelogwebservice')}}">
                                            <img src="{{asset('/ico/systemfunctions.png')}}" alt="" class="img-25 ml-1">
                                        </a>
                                    </td>
                                    <td>General</td>
                                </tr>
                                <tr>
                                    <td class="font-12"> Enviar mensajes de nuevas incidencias.</td>
                                    <td>
                                        <a href="{{url('newincidencejourneys')}}">
                                            <img src="{{asset('/ico/systemfunctions.png')}}" alt="" class="img-25 ml-1">
                                        </a>
                                    </td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td class="font-12"> Enviar mensajes de unidades sin arrivar a carga.</td>
                                    <td>
                                        <a href="{{url('sendmessagenotarriveunittoshipment')}}">
                                            <img src="{{asset('/ico/systemfunctions.png')}}" alt="" class="img-25 ml-1">
                                        </a>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-12"> Obtener PODs de RControl.</td>
                                    <td>
                                        <a href="{{url('getevidencesfromrcontrol')}}">
                                            <img src="{{asset('/ico/systemfunctions.png')}}" alt="" class="img-25 ml-1">
                                        </a>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-12"> Enviar mensaje de cajas autorizadas sin salir</td>
                                    <td>
                                        <a href="{{url('sendMessageExitUnitTime')}}">
                                            <img src="{{asset('/ico/systemfunctions.png')}}" alt="" class="img-25 ml-1">
                                        </a>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-12"> Enviar mensaje unidades sin enrampar de embarques</td>
                                    <td>
                                        <a href="{{url('sendNotEnsnareUnit')}}">
                                            <img src="{{asset('/ico/systemfunctions.png')}}" alt="" class="img-25 ml-1">
                                        </a>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-12"> Obtener la ETA de los embaques</td>
                                    <td>
                                        <a href="{{url('getETAForShipment')}}">
                                            <img src="{{asset('/ico/systemfunctions.png')}}" alt="" class="img-25 ml-1">
                                        </a>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-12"> Borra el log de envios de mensajes de whatsapp.</td>
                                    <td>
                                        <a href="{{url('deletelogwhatsapp')}}">
                                            <img src="{{asset('/ico/systemfunctions.png')}}" alt="" class="img-25 ml-1">
                                        </a>
                                    </td>
                                    <td>General</td>
                                </tr>
                                <tr>
                                    <td class="font-12"> Eliminar usuarios sin inicio de sesion.</td>
                                    <td>
                                        <a href="{{url('deleteusers')}}">
                                            <img src="{{asset('/ico/systemfunctions.png')}}" alt="" class="img-25 ml-1">
                                        </a>
                                    </td>
                                    <td>General</td>
                                </tr>
                                <tr>
                                    <td class="font-12"> Cancelar recibos programados.</td>
                                    <td>
                                        <a href="{{url('clearReceiptAsn')}}">
                                            <img src="{{asset('/ico/systemfunctions.png')}}" alt="" class="img-25 ml-1">
                                        </a>
                                    </td>
                                    <td>General</td>
                                </tr>
                                <tr>
                                    <td class="font-12"> Enviar mensaje de whatsapp de arribo de proveedor.</td>
                                    <td>
                                        <a href="{{url('sendmessagefornewarrivereceipt')}}">
                                            <img src="{{asset('/ico/systemfunctions.png')}}" alt="" class="img-25 ml-1">
                                        </a>
                                    </td>
                                    <td>Notificaciones</td>
                                </tr>
                                <tr>
                                    <td class="font-12"> Enviar correo de arribo de proveedor.</td>
                                    <td>
                                        <a href="{{url('sendmessagefornewarrivereceipt')}}">
                                            <img src="{{asset('/ico/systemfunctions.png')}}" alt="" class="img-25 ml-1">
                                        </a>
                                    </td>
                                    <td>Notificaciones</td>
                                </tr>
                                <tr>
                                    <td class="font-12"> Enviar correo de unidad con diferencia de peso.</td>
                                    <td>
                                        <a href="{{url('sendmaiilfordifferenceweight')}}">
                                            <img src="{{asset('/ico/systemfunctions.png')}}" alt="" class="img-25 ml-1">
                                        </a>
                                    </td>
                                    <td>Notificaciones</td>
                                </tr>
                                <tr>
                                    <td class="font-12"> Enviar mensaje de unidad con diferencia de peso.</td>
                                    <td>
                                        <a href="{{url('sendmessagefordifferenceweight')}}">
                                            <img src="{{asset('/ico/systemfunctions.png')}}" alt="" class="img-25 ml-1">
                                        </a>
                                    </td>
                                    <td>Notificaciones</td>
                                </tr>
                                <tr>
                                    <td class="font-12">Crear UUID a Usuario</td>
                                    <td>
                                        <a href="{{url('createuuidtouser')}}">
                                            <img src="{{asset('/ico/systemfunctions.png')}}" alt="" class="img-25 ml-1">
                                        </a>
                                    </td>
                                    <td>Notificaciones</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('css')

@endsection


@section('script')
    <script>
        var oTable = $(".tablegeneral").DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "serverSide": false,
            'colReorder': true,
            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            "lengthMenu": [[30, 50, 100, 150, 200, 250, -1], [30, 50, 100, 150, 200, 250, "Todos"]],

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
