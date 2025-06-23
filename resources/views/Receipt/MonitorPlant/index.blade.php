@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Monitor de arribos de planta
                    <span class="float-right">Última actualización: <span id="lastupdate"></span></span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="shipmentId">Número de embarque:</label>
                                <input type="text" class="form-control" id="shipmentId" name="shipmentId">
                            </div>
                        </div>
                        <div class="col-lg-2 col- col-md-6 col-sm-12 align-self-center">
                            <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                <button id="filterBtn" class="btn waves-effect waves-light btn-outline-info btn-sm">
                                    <img src="{{asset('/ico/adbuscar.png')}}" alt="" class="img-16 ml-1">
                                    <span style="font-size: 11px;"> Buscar</span>
                                </button>
                                <button type="button" id="clearBtn"
                                        class="btn waves-effect waves-light btn-outline-info btn-sm">
                                    <img src="{{asset('/ico/addestroy.png')}}" alt="" class="img-16 ml-1">
                                    <span style="font-size: 11px;"> Limpiar filtros</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="mt-2">
                <div id="cards-container"></div>
            </div>
            <div class="card-footer">

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function createCards(dataArray) {
            let cardsHtml = '';
            let rowsData = [];
            for (let i = 0; i < dataArray.length; i += 4) {
                rowsData.push(dataArray.slice(i, i + 4));
            }
            rowsData.forEach((rowData) => {
                cardsHtml += '<div class="row">';
                rowData.forEach((data) => {
                    cardsHtml += `<div class="col-lg-3">
               <div class="card" style="border-radius: 10px">
                  <div class="card-header" style="font-size: 16px;font-weight: bold;border-top: 1px solid #78909c;border-bottom: 1px solid #78909c">
                  Recibo :  ${data.shipmentid}
                  </div>
                  <div class="card-body">
                    <table class="table table-condensed">
                      <tr>
                        <td style="font-weight: bold">Linea de transporte:</td>
                        <td style="padding-left: 30px">${data.carriername}</td>
                      </tr>
                      <tr>
                        <td style="font-weight: bold">Nombre del operador:</td>
                        <td style="padding-left: 30px">${data.operatorname}</td>
                      </tr>
                      <tr>
                        <td style="font-weight: bold">Telefono del operador:</td>
                        <td style="padding-left: 30px">${data.phone}</td>
                      </tr>
                      <tr>
                        <td style="font-weight: bold">Placa de Caja:</td>
                        <td style="padding-left: 30px">${data.boxnumber}</td>
                      </tr>
                      <tr>
                        <td style="font-weight: bold">Placa de tracto:</td>
                        <td style="padding-left: 30px">${data.trucknumber}</td>
                      </tr>
                      <tr>
                        <td style="font-weight: bold">Fecha y Hora de salida:</td>
                        <td style="padding-left: 30px">${data.checkout}</td>
                      </tr>
                    </table>
                    <hr style="border: 1px dashed">
                    <h3 class="text-center">${data.location}</h3>
                  </div>
                </div>
              </div>`;
                });
                cardsHtml += '</div>';
            });
            document.getElementById('cards-container').innerHTML = cardsHtml;
        }
    </script>

    <script>
        document.getElementById('filterBtn').addEventListener('click', fetchDataAndUpdate);
        fetchDataAndUpdate();
        setInterval(fetchDataAndUpdate, 180000);

        function fetchDataAndUpdate() {
            const shipmentId = document.getElementById('shipmentId').value;
            $.ajax({
                url: '{{route('getReceiptInTransit')}}',
                method: 'GET',
                data: {shipmentid: shipmentId},
                success: function (data) {
                    let currentDate = setDateInLabel();
                    const dataArray = JSON.parse(data);
                    createCards(dataArray);
                }
            });
        }
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

        $('#clearBtn').click(function () {
            document.getElementById('cards-container').innerHTML = '';
            document.getElementById('shipmentId').value = '';
            fetchDataAndUpdate();
        });
    </script>
@endsection
