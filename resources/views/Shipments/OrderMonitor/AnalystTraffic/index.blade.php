@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card filter-card">
                <div class="card-header">
                    Monitor de tráfico y facturación
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form" class="r-separator">
                        {{csrf_field()}}
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <a href="{{route('menushipment')}}" >
                                    @include('buttons.cancel')
                                </a>
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-0">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="turnid">Turno</label>
                                    <select name="turnid" id="turnid" class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($turns as $model)
                                            <option value="{{$model->id}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="shipmentid">Embarque</label>
                                    <input type="text" class="form-control"  id="shipmentid" name="shipmentid">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusid">Estatus</label>
                                    <select name="statusid" id="statusid" class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($status as $model)
                                            <option value="{{$model->statusid}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="workday">Fecha de trabajo</label>
                                    <input type="text" class="form-control"  id="workday" name="workday">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3" id="traffic-kpis">
        <div class="col-6 col-md-4 col-lg-2 mb-2">
            <div class="kpi-mini kpi-total h-100">
                <div class="kpi-lbl">Total registros</div>
                <div class="kpi-val" id="kpi-mt-total">—</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2 mb-2">
            <div class="kpi-mini kpi-released h-100">
                <div class="kpi-lbl">Con llaves entregadas</div>
                <div class="kpi-val" id="kpi-mt-with-keys">—</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2 mb-2">
            <div class="kpi-mini kpi-nokey h-100">
                <div class="kpi-lbl">Llaves pendientes</div>
                <div class="kpi-val" id="kpi-mt-without-keys">—</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2 mb-2">
            <div class="kpi-mini kpi-released h-100">
                <div class="kpi-lbl">En tiempo</div>
                <div class="kpi-val" id="kpi-mt-ontime">—</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2 mb-2">
            <div class="kpi-mini kpi-critical h-100">
                <div class="kpi-lbl">Fuera de tiempo</div>
                <div class="kpi-val" id="kpi-mt-late">—</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2 mb-2">
            <div class="kpi-mini kpi-nokey h-100">
                <div class="kpi-lbl">Sin reportarse</div>
                <div class="kpi-val" id="kpi-mt-not-reported">—</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <table id="table-traffics" class="table table-bordered  table-condensed  table-hover table-uppercase monitor-table">
                                <thead>
                                <tr>
                                    <th >Opciones</th>
                                    <th >¿Datos?</th>
                                    <th >Fecha</th>
                                    <th >Turno</th>
                                    <th >Embarque</th>
                                    <th >Estatus</th>
                                    <th >Precarga</th>
                                    <th >Cortina</th>
                                    <th >¿Llaves?</th>
                                    <th >Cliente</th>
                                    <th >Destino</th>
                                    <th >Cita de carga</th>
                                    <th >E.de llegada</th>
                                    <th >F.Patios</th>
                                    <th >Linea transportista</th>
                                    <th>Operador</th>
                                    <th >Placa de caja</th>
                                    <th >F.Entrada</th>
                                    <th >Estadia</th>
                                    <th >Cita con cliente</th>
                                    <th >Salida maxima</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/quill/dist/quill.snow.css')}}">
    <style>
        .filter-card {
            border-radius: 8px;
            border: 1px solid #E8E8E8;
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
        }
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

        .monitor-table thead th {
            font-size: .70rem;
            letter-spacing: .03em;
            text-transform: uppercase;
            font-weight: 700;
        }
    </style>
@endsection

@section('script')
    @include('Shipments.OrderMonitor.AnalystTraffic.partials.table')
    <script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>
    <script
        src="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js')}}"></script>
    <script>
        $('#workday').bootstrapMaterialDatePicker({format: 'YYYY-MM-DD', weekStart: 1, time: false});
    </script>
    <script>
        function reloadtable() {
            $('#table-traffics').DataTable().ajax.reload();
            loadMonitorTrafficKpis();
        }

        /* Medibles principales del monitor de tráfico (llaves y estatus de
           llegada), respetando los filtros que se muestran en la parte
           superior de la vista. */
        function loadMonitorTrafficKpis() {
            $.ajax({
                url: '{{ route("getmonitortrafickpis") }}',
                data: getMonitorTrafficFilterParams(),
                success: function (r) {
                    $('#kpi-mt-total').text(r.total);
                    $('#kpi-mt-with-keys').text(r.with_keys);
                    $('#kpi-mt-without-keys').text(r.without_keys);
                    $('#kpi-mt-ontime').text(r.on_time);
                    $('#kpi-mt-late').text(r.late);
                    $('#kpi-mt-not-reported').text(r.not_reported);
                }
            });
        }
    </script>
    <script>
        $("#statusid,#turnid,#warehouseid").change(function () {
            reloadtable();
        });
    </script>
    <script>
        $(document).ready(function () {
            loadMonitorTrafficKpis();
        });
    </script>
@endsection
