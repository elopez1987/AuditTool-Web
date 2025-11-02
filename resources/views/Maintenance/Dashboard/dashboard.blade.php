@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    asignacion de equipos
                    <span class="float-right">Última actualización: <span id="lastupdate"></span></span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="filter">Número de serie:</label>
                            <input type="text" id="serialnumber" class="form-control text-uppercase"
                                   name="serialnumber">
                        </div>
                        <div class="col-lg-3">
                            <label for="filter">Número de equipo:</label>
                            <input type="text" id="numbertruck" class="form-control text-uppercase" name="numbertruck">
                        </div>
                        <div class="col-lg-3">
                            <label for="filter">Nombre del operador:</label>
                            <input type="text" id="userassigned" class="form-control text-uppercase"
                                   name="userassigned">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table width="100%" id="table-lifttruck" class="table table-bordered table-hover table-condensed ">
                                <thead>
                                    <tr>
                                        <th class="bg-inverse text-white">Número de serie</th>
                                        <th class="bg-inverse text-white">Número de equipo</th>
                                        <th class="bg-inverse text-white">Tipo</th>
                                        <th class="bg-inverse text-white">Estatus de equipo</th>
                                        <th class="bg-inverse text-white">Asignado</th>
                                        <th class="bg-inverse text-white">F. Asignación</th>
                                        <th class="bg-inverse text-white">Tiempo transcurrido</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
                const statusColorToClass = {
                    'MT-A': 'bg-grey',
                    'MT-B': 'bg-red text-white',
                    'MT-C': 'bg-green',
                };
                $.ajax({
                    url: "{{url('getTruckAssigned')}}",
                    method: "GET",
                    success: function (data) {
                        let currentDate = setDateInLabel();

                        let html = '';
                        $.each(data, function (key, value) {
                            let {minutos, horas} = getDiffTime(value.startdate, currentDate);
                            const className = statusColorToClass[value.statusid];
                            html += '<tr>';
                            html += '<td>' + value.serialnumber + '</td>';
                            html += '<td>' + value.numbertruck + '</td>';
                            html += '<td>' + value.typetruck + '</td>';
                            if (className) {
                                html += '<td class="bg ' + className + '">' + value.statustruck + '</td>';
                            }
                            let userassigned = value.userassigned != null ? value.userassigned : '-';
                            html += '<td>' + userassigned + '</td>';
                            let startdate = value.startdate != null ? value.startdate : '-';
                            html += '<td>' + startdate + '</td>';
                            if (value.startdate !== undefined && value.startdate !== null && value.startdate.length > 0) {
                                html += '<td class="bg bg-green">' + `${horas % 24} horas ,${minutos % 60} minutos ` + '</td>';
                            } else {
                                html += '<td >' + `` + '</td>';
                            }
                            html += '</tr>';
                        });
                        $('#table-lifttruck tbody').html(html);
                    }
                });
            }
            fetch_data();
            setInterval(fetch_data, 60000);
        });
    </script>
    <script>
        function getDiffTime(startdate, currentDate) {
            let fecha1 = new Date(startdate);
            let fecha2 = new Date(currentDate);
            let diferencia = fecha2 - fecha1;
            let segundos = Math.floor(diferencia / 1000);
            let minutos = Math.floor(segundos / 60);
            let horas = Math.floor(minutos / 60);
            return {minutos, horas};
        }
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
            $("#serialnumber,#numbertruck,#userassigned").on("keyup", function () {
                let value = $(this).val().toLowerCase();
                $("#table-lifttruck tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
@endsection
