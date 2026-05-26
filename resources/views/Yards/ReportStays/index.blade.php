@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Reporte de estadias unidades cargadas
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-input">
                                <form method="GET" id="search" role="form">
                                    {{csrf_field()}}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a>
                                                @include('buttons.search')
                                            </a>
                                            <a>
                                                @include('buttons.reloadajax')
                                            </a>
                                            <a href="{{route('home')}}">
                                                @include('buttons.cancel')
                                            </a>
                                            <a href="" data-target="#modal-filter" data-toggle="modal">
                                                <button type="submit" class="btn waves-effect waves-light btn-outline-info btn-md">
                                                    <img src="{{asset('/ico/filter.png')}}" alt="" class="img-16 ml-1">
                                                    <span class="text-button"> + Filtros</span>
                                                </button>
                                            </a>
                                            @include('Yards.ReceiptUnits.filters')
                                        </div>
                                    </div>
                                    <hr class="mt-3 mb-3">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="folionumber">Número de folio</label>
                                                <input type="text" class="form-control numeric" id="folionumber" name="folionumber"
                                                       onKeyUp=this.value=this.value.toUpperCase()
                                                >
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="barcode">Código de barra</label>
                                                <input type="text" class="form-control numeric" id="barcode" name="barcode"
                                                       onKeyUp=this.value=this.value.toUpperCase()
                                                >
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="boxnumber">Placa de caja</label>
                                                <input type="text" class="form-control" id="boxnumber" name="boxnumber"
                                                       onKeyUp=this.value=this.value.toUpperCase()
                                                >
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="econumber">Número de caja</label>
                                                <input type="text" class="form-control" id="econumber" name="econumber"
                                                       onKeyUp=this.value=this.value.toUpperCase()
                                                >
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12 ">
                                            <div class="form-group">
                                                <label for="shipmentid">Número de embarque</label>
                                                <input type="text" class="form-control" id="shipmentid" name="shipmentid"
                                                       onKeyUp=this.value=this.value.toUpperCase()
                                                >
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 p-4">
                        <div class="col-lg-12 col-xs-12">
                            <div class="card-input">
                                <table id="table-stays" class="table table-bordered  table-condensed">
                                    <thead>
                                    <tr>
                                        <th class="bg-inverse text-white">Bodega</th>
                                        <th class="bg-inverse text-white">Folio</th>
                                        <th class="bg-inverse text-white">Embarque</th>
                                        <th class="bg-inverse text-white">Linea</th>
                                        <th class="bg-inverse text-white">Placa de Caja</th>
                                        <th class="bg-inverse text-white">Eco. de Caja</th>
                                        <th class="bg-inverse text-white">Tipo de Unidad</th>
                                        <th class="bg-inverse text-white">Capacidad de Unidad</th>
                                        <th class="bg-inverse text-white">Ubicación</th>
                                        <th class="bg-inverse text-white">Estatus de Unidad</th>
                                        <th class="bg-inverse text-white">Estatus de Folio</th>
                                        <th class="bg-inverse text-white">F.Entrada</th>
                                        <th class="bg-inverse text-white">F.Autorización</th>
                                        <th class="bg-inverse text-white">Motivo de Entrada</th>
                                        <th class="bg-inverse text-white">Origen</th>
                                        <th class="bg-inverse text-white">Estadía Total</th>
                                        <th class="bg-inverse text-white">Tiempo de autorización de salida</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <span>Reporte de estadias</span>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    @include('Yards.ReportStays.partials.table')
    <script>
        $("#carrierid,#rasonid,#statusid,#warehouseid,#unitstatusid").change(function () {
            reloadtable();
        });
        $("#barcode,#folionumber,#trucknumber,#shipmentid").blur(function () {
            reloadtable();
        });
        $(document).ready(function () {
            $("#carrierid").select2({
                dropdownParent: $("#modal-filter")
            });
            $("#rasonid").select2({
                dropdownParent: $("#modal-filter")
            });
            $("#warehouseid").select2({
                dropdownParent: $("#modal-filter")
            });
            $("#statusid").select2({
                dropdownParent: $("#modal-filter")
            });
            $("#unitstatusid").select2({
                dropdownParent: $("#modal-filter")
            });
        });
    </script>
    <script>
        function reloadtable() {
            $('#table-stays').DataTable().ajax.reload();
        }
    </script>
@endsection
