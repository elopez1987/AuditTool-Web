@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Consulta general de logs.
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('menushipment')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                    </form>
                    <hr class="mb-3">
                    <div class="row">
                        <div class="col-10">
                            <table id="table-logs" class="table table-bordered  table-condensed">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">Tranferencia</th>
                                    <th class="bg-inverse text-white">Mensaje</th>
                                    <th class="bg-inverse text-white">F.Movimiento</th>
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
    <link rel="stylesheet"
          href="{{asset('/theme/assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
@endsection


@section('script')
    <script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script>
        jQuery('.mydatepicker').datepicker(
            {
                autoclose: true,
                orientation: 'bottom',
                format: 'yyyy-mm-dd',
            }
        );
    </script>
    @include('Configuration.EdiLogs.LogGeneral.partials.table')
    <script>
        function reloadtable() {
            $('#table-logs').DataTable().ajax.reload();
        }
    </script>
@endsection
