@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                   Unidades sin arribar a carga
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
                                    <span class=" mr-2 ml-2 font-12"> Buscar</span>
                                </button>
                                <button type="button" id="clearBtn"
                                        class="btn waves-effect waves-light btn-outline-info btn-sm">
                                    <img src="{{asset('/ico/addestroy.png')}}" alt="" class="img-16 ml-1">
                                    <span class="ml-2 mr-2 font-12"> Limpiar filtros</span>
                                </button>
                                <button type="button" id="reloaddata"
                                        class="btn waves-effect waves-light btn-outline-info btn-sm">
                                    <img src="{{asset('/ico/changelocation.png')}}" alt="" class="img-16 ml-1">
                                    <span class="ml-2 mr-2 font-12" > Recargar datos</span>
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
                cardsHtml += '<div class="row" >';
                rowData.forEach((data) => {
                    let operatorName = data.operatorname ? data.operatorname : 'SIN ARRIBO DE UNIDAD';
                    cardsHtml += `<div class="col-lg-3">
               <div class="card" style="border-radius: 10px;margin-left: 10px;border: 1px solid #ff1744">
                  <div   style="font-size: 12px;font-weight: bold;background-color: #ff1744;color: white;border-top-left-radius: 5px;border-top-right-radius:5px;padding: 5px;">
                    <span style="margin-left: 10px"> Embarque: </span>
                             <a href="./orderdeliveries/${data.id}" style="color: white;margin-left: 10px;text-decoration: none;font-size: 14px;font-weight: bold;">
                ${data.shipmentid}
            </a>
                  </div>
                  <div class="card-body">
                    <table class="table table-condensed table-bordered tale-hover">
                      <tr>
                        <td style="font-weight: bold">Linea de transporte:</td>
                        <td style="padding-left: 30px">${data.carriername}</td>
                      </tr>
                      <tr>
                        <td style="font-weight: bold">Nombre de operador:</td>
                        <td style="padding-left: 30px"> <span >${operatorName}</span></td>
                      </tr>
                      <tr>
                        <td style="font-weight: bold">Cliente:</td>
                        <td style="padding-left: 30px">${data.customername}</td>
                      </tr>
                      <tr>
                        <td style="font-weight: bold">Destino:</td>
                        <td style="padding-left: 30px">${data.city}</td>
                      </tr>
                      <tr>
                        <td style="font-weight: bold">Fecha de carga:</td>
                        <td style="padding-left: 30px;color: red">${data.loadingdate}</td>
                      </tr>
                      <tr>
                        <td style="font-weight: bold">Fecha de entrega:</td>
                        <td style="padding-left: 30px">${data.downloaddate}</td>
                      </tr>
                    </table>
                    <hr style="border: 1px solid;color: #0d47a1">
                    <h5 class="text-center text-trax font-bold" style="color:#546e7a">${data.status}</h5>
                    <hr style="border: 1px solid;color:#0d47a1;">
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

        function fetchDataAndUpdate() {
            const shipmentId = document.getElementById('shipmentId').value;
            $.ajax({
                url: '{{route('getShipmentStatusWithoutArrival')}}',
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
        fetchDataAndUpdate();
        setInterval(fetchDataAndUpdate, 180000);
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

        $('#reloaddata').click(function () {
            fetchDataAndUpdate();
            setDateInLabel();
        });


    </script>
@endsection
