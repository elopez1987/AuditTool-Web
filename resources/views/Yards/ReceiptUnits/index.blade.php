@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Inventario de cajas
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12">
                                @can('receiptunits.create')
                                    <a href="{{route('receiptunits.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a>
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
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
                        <hr class="m-t-1">
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
    </div>
    <div class="row">
        <div class="col-lg-12 ">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table-boxsotck"
                                   class="table table-bordered  table-condensed table-hover table-uppercase">
                                <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>C.Distribución</th>
                                    <th>Bodega</th>
                                    <th>Folio</th>
                                    <th>Embarque/Recibo</th>
                                    <th>C.Barra</th>
                                    <th>Linea de transporte</th>
                                    <th>Placas de caja</th>
                                    <th>Número de caja</th>
                                    <th>Placas de tracto</th>
                                    <th>Nombre del operador</th>
                                    <th>Celular</th>
                                    <th>Zona</th>
                                    <th>Ubicación</th>
                                    <th>E. de Caja</th>
                                    <th>Estatus</th>
                                    <th>Tipo de Caja</th>
                                    <th>Categoria</th>
                                    <th>Concepto</th>
                                    <th>F.Ingreso</th>
                                    <th>Origen</th>
                                    <th>Estadia</th>
                                    <th>Folio de tracto</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    Control de patios / Cajas en patio / Existencia
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')

@endsection


@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
    @include('Yards.ReceiptUnits.partials.table')
    <script>
        $(document).ready(function () {
            setInterval(reloadtable, 1000 * 60 * 5);
        });
    </script>
    <script>
        $("#carrierid,#rasonid,#statusid,#warehouseid").change(function () {
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
        });
    </script>
    <script>
        function reloadtable() {
            $('#table-boxsotck').DataTable().ajax.reload();
        }
    </script>
    <script>
        let route = "{{url('autocompleteboxnumber')}}";
        $('#boxnumber').typeahead({
            source: function (query, process) {
                return $.get(route, {
                    query: query
                }, function (data) {
                    return process(data);
                });
            }
        });
    </script>
@endsection
