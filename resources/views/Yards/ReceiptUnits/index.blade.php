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
                                <a href="javascript:void(0);" onclick="exportAllReceiptUnits()">
                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img src="{{asset('/pics/adexportar.png')}}" alt="" class="img-16 ml-1">
                                        <span class="text-button"> Exportar Todo</span>
                                    </button>
                                </a>
                                @include('Yards.ReceiptUnits.filters')
                            </div>
                        </div>
                        <hr class="m-t-1">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12 ">
                                <div class="form-group">
                                    <label for="warehouseid">Bodega </label>
                                    <select name="warehouseid" id="warehouseid" class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{$warehouse->warehouseid}}"> {{$warehouse->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
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
    <div class="row mb-3" id="receiptunits-kpis">
        <div class="col-6 col-lg-3 mb-2">
            <div class="kpi-mini kpi-total h-100">
                <div class="kpi-lbl">Total en patio</div>
                <div class="kpi-val" id="kpi-ru-total">—</div>
            </div>
        </div>
        <div class="col-6 col-lg-3 mb-2">
            <div class="kpi-mini kpi-critical h-100">
                <div class="kpi-lbl">Estadía crítica (&gt;30 días)</div>
                <div class="kpi-val" id="kpi-ru-critical">—</div>
            </div>
        </div>
        <div class="col-6 col-lg-3 mb-2">
            <div class="kpi-mini kpi-nokey h-100">
                <div class="kpi-lbl">Sin ubicación asignada</div>
                <div class="kpi-val" id="kpi-ru-nolocation">—</div>
            </div>
        </div>
        <div class="col-6 col-lg-3 mb-2">
            <div class="kpi-mini kpi-released h-100">
                <div class="kpi-lbl">Ingresos de hoy</div>
                <div class="kpi-val" id="kpi-ru-today">—</div>
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
<style>
    .kpi-mini {
        border-radius: 10px;
        padding: 1.1rem 1.25rem;
        border: 1px solid transparent;
    }
    .kpi-mini .kpi-lbl {
        font-size: .72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .03em;
        margin-bottom: .4rem;
    }
    .kpi-mini .kpi-val {
        font-size: 2.2rem; font-weight: 700; line-height: 1;
    }
    .kpi-total    { background: #fff; border-color: #E8E8E8; }
    .kpi-total    .kpi-lbl { color: #5A5A5A; }
    .kpi-total    .kpi-val { color: #2B2F33; }

    .kpi-critical { background: #FEE2E2; border-color: #FCA5A5; }
    .kpi-critical .kpi-lbl { color: #EF4444; }
    .kpi-critical .kpi-val { color: #DC2626; }

    .kpi-nokey    { background: #FEF3C7; border-color: #FCD34D; }
    .kpi-nokey    .kpi-lbl { color: #D97706; }
    .kpi-nokey    .kpi-val { color: #B45309; }

    .kpi-released { background: #D1FAE5; border-color: #6EE7B7; }
    .kpi-released .kpi-lbl { color: #059669; }
    .kpi-released .kpi-val { color: #047857; }
</style>
@endsection


@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
    @include('Yards.ReceiptUnits.partials.table')
    <script>
        $(document).ready(function () {
            loadReceiptUnitsKpis();
            setInterval(reloadtable, 1000 * 60 * 5);
        });
    </script>
    <script>
        $("#carrierid,#rasonid,#statusid,#warehouseid,#unitstatusid").change(function () {
            reloadtable();
        });
        $("#barcode,#folionumber,#trucknumber,#shipmentid,#location").blur(function () {
            reloadtable();
        });
        $(document).ready(function () {
            $("#carrierid").select2({
                dropdownParent: $("#modal-filter")
            });
            $("#rasonid").select2({
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
            $('#table-boxsotck').DataTable().ajax.reload();
            loadReceiptUnitsKpis();
        }

        /* Medibles principales de la existencia de cajas, respetando los mismos
           filtros que se muestran en pantalla y en el modal de filtros. */
        function loadReceiptUnitsKpis() {
            $.ajax({
                url: '{{ route("getReceiptUnitsKpis") }}',
                data: getReceiptUnitsFilterParams(),
                success: function (r) {
                    $('#kpi-ru-total').text(r.total);
                    $('#kpi-ru-critical').text(r.critical_stay);
                    $('#kpi-ru-nolocation').text(r.no_location);
                    $('#kpi-ru-today').text(r.entries_today);
                }
            });
        }

        /* Exporta a Excel todos los registros que cumplen con los filtros
           actuales, no solo lo mostrado en la página actual del datatable. */
        function exportAllReceiptUnits() {
            var params = $.param(getReceiptUnitsFilterParams());
            window.location.href = '{{ route("exportallreceiptunits") }}' + '?' + params;
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
