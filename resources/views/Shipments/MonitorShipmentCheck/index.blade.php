@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Monitor de verificacion
                    <span class="float-right">Última actualización: <span id="lastupdate"></span></span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="filter">Número de Embarque:</label>
                            <input type="text" id="shipmentid" class="form-control text-uppercase" name="shipmentid">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table width="100%" id="tablecheck" class="table table-bordered table-condensed table-hover  ">
                                <thead>
                                    <tr >
                                        <th ><span class="font-11 p-4">Embarque</span></th>
                                        <th ><span class="font-11 p-4">Cliente</span></th>
                                        <th ><span class="font-11 p-4">Verificador</span></th>
                                        <th  width="30%"><span class="font-11 p-4">% de Avance</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
        <script>
            $(document).ready(function () {
                function fetch_data() {
                    $.ajax({
                        url: "{{url('getDataStatusShipmentchek')}}",
                        method: "GET",
                        success: function (data) {
                            let currentDate = setDateInLabel();
                            let html = '';
                            $.each(data, function (key, value) {
                                html += '<tr>';
                                // html += '<td style="font-size: 12px !important;font-weight:bold;"> ' + value.shipmentid + '  </td>';
                                html += '<td style="text-align: center">' + '<a style="font-weight: bold;color: black;text-align: center;font-size: 18px;" href="./monitoroperation/' + value.id + '/monitorcheck"> ' + value.shipmentid + ' </a>' + '</td>';
                                html += '<td style="font-size: 12px !important;font-weight:bold;text-align: center">' + value.customername + '</td>';
                                html += '<td style="font-size: 12px !important;font-weight:bold;text-align: center">' + value.username + '</td>';
                                html += '<td>';
                                html += "<div class='col-md-12' style='font-size: 15px !important'>";
                                var percentage = ((100 / value.totalpedido) * value.verificado).toFixed(2);
                                html += "<h5> <span class='pull-right' style='font-size: 13px;'>" + percentage + "%</span></h5>";
                                html += "<div class='progress' style='height: 30px;'>";
                                html += "<div class='progress-bar bg-danger' style='width:" + percentage + "%; height:30px !important;' role='progressbar'><span class='sr-only'>100 completado</span></div>";
                                html += "</div>";
                                html += "</div>";
                                html += '</td>';
                                html += '</tr>';
                            });
                            $('#tablecheck tbody').html(html);
                        }
                    });
                }
                fetch_data();
                setInterval(fetch_data, 60000);
            });
        </script>
        <script>
            function setDateInLabel() {
                let fechaActual = new Date();
                let fecha = fechaActual.getFullYear() + '-' + ("0" + (fechaActual.getMonth() + 1)).slice(-2) + '-' + ("0" + fechaActual.getDate()).slice(-2);
                let hora = ("0" + fechaActual.getHours()).slice(-2) + ":" + ("0" + fechaActual.getMinutes()).slice(-2) + ":" + ("0" + fechaActual.getSeconds()).slice(-2);
                let currentDate = fecha + ' ' + hora;
                localStorage.setItem('currentDate', currentDate);
                document.getElementById('lastupdate').textContent = localStorage.getItem('currentDate');
                return currentDate;
            }
        </script>
        <script>
            $(document).ready(function () {
                $("#shipmentid").on("keyup", function () {
                    let value = $(this).val().toLowerCase();
                    $("#tablecheck tbody tr").filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
            });
        </script>
@endsection
