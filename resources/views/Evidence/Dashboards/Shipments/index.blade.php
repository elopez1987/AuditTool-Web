@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    seguimiento
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#view01"
                                                role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span>
                                <span class="hidden-xs-down">Directos</span></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="view01" role="tabpanel">
                            <form id="filterForm">
                                <div class="row mt-4">
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="shipmentid">Embarque</label>
                                            <input type="text" class="form-control" id="shipmentid" name="shipmentid"
                                                   onKeyUp=this.value=this.value.toUpperCase()
                                            >
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Fecha de trabajo</label>
                                            <div class='input-group mb-3'>
                                                <input type='text' id="daterange" name="daterange" class="form-control daterange"/>
                                                <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <span class="ti-calendar"></span>
                                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col- col-md-6 col-sm-12 align-self-center">
                                        <div class="btn-group btn-group-lg" role="group" aria-label="Basic example">
                                            <button type="submit"
                                                    class="btn waves-effect waves-light btn-outline-info btn-sm">
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
                            </form>
                            <hr class="mt-2">
                            <div class="p-20">
                                <div class="row" style="height: 400px;overflow-y: scroll;">
                                    <div id="cardsShipments" class="col-lg-12"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        Seguimiento de embarques
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('theme/assets/libs/daterangepicker/daterangepicker.css')}}">
@endsection


@section('script')
    <script src="{{asset('/theme/assets/libs/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>
    <script>
        $('.daterange').daterangepicker();
    </script>

    <script>
        $(function () {
            let start_date = moment().subtract(1, 'M');
            let end_date = moment();
            $('#daterange span').html(start_date.format('YYYY-MM-DD') + ' - ' + end_date.format('YYYY-MM-DD'));
            $('#daterange').daterangepicker({
                startDate: start_date,
                endDate: end_date
            }, function (start_date, end_date) {
                $('#daterange span').html(start_date.format('YYYY-MM-DD') + ' - ' + end_date.format('YYYY-MM-DD'));
            });
        });

        $('#filterForm').on('submit', function (e) {
            e.preventDefault();
            document.getElementById('cardsShipments').innerHTML = '';
            let shipmentid = $('#shipmentid').val();
            let from_date = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD');
            let to_date = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD');
            $.ajax({
                type: 'GET',
                url: '{{url('getDataToDashboardShipmentProcess')}}', // The URL to your Laravel controller method
                data: {
                    shipmentid: shipmentid,
                    from_date: from_date,
                    to_date: to_date
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        var shipments = response.data; // Array of shipments
                        var rowHTML = "<div class='row'>";
                        var columnIndex = 0;
                        shipments.forEach(function (shipment) {
                            var cardHTML = `
                                 <div class="col-md-3"><!-- Adjust as needed, col-md-3 for 4 columns -->
                        <div class="card-input" style="border-radius: 20px">
                            <div class="card order-widget" style="border-radius: 20px">
                                <a href="storageevidenceprocess/${shipment.recordid}">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h3 class="card-title font-bold text-danger">${shipment.shipmentid} | ${shipment.workday}</h3>
                                                <h4 class="card-subtitle m-b-2">${shipment.customer}</h4>
                                                <div class="row">
                                                    <div class="col-4 border-right text-center">
                                                        <h3 class="m-b-0 font-medium">${shipment.solicitado}</h3>
                                                        <span>Solicitado</span>
                                                    </div>
                                                    <div class="col-4 border-right text-center">
                                                        <h3 class="m-b-0 font-medium">${shipment.verificado}</h3>
                                                        <span>Verificado</span>
                                                    </div>
                                                    <div class="col-4 text-center">
                                                        <h3 class="m-b-0 font-medium">${shipment.cargado}</h3>
                                                        <span>Cargado</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>`;
                            rowHTML += cardHTML; // Add card to current row
                            columnIndex += 1;
                            if (columnIndex === 4) { // four cards per row
                                rowHTML += "</div><div class='row'>"; // close the current row and open a new one
                                columnIndex = 0; // reset column index for new row
                            }
                        });
                        rowHTML += "</div>"; // Close the final row

                        $('#cardsShipments').append(rowHTML); // Add rows to your chosen container
                    }
                }
            });
        });
    </script>
    <script>
        $('#clearBtn').click(function () {
            document.getElementById('cardsShipments').innerHTML = '';
            $(':input', '#filterForm')
                .not(':button, :submit, :reset, :hidden')
                .val('')
                .prop('checked', false)
                .prop('selected', false);
        });
    </script>
@endsection
