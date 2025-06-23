@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Catalogo de impresoras
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('systemprinters.create')
                                    <a href="{{route('systemprinters.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('menuconfiguration')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="description">Descripción <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="description" name="description"
                                           aria-describedby="description" onKeyUp=this.value=this.value.toUpperCase();
                                           value="{{old('description')}}">
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr class="mt-2">
                    <div class="row">
                        <div class="col-lg-12 col-xs-12">
                            <table id="table-printer"
                                   class="table table-bordered table-striped table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th>C.Distribución</th>
                                    <th>Impresora</th>
                                    <th>IP</th>
                                    <th>Comando</th>
                                    <th>Auxiliar 01</th>
                                    <th>Auxiliar 02</th>
                                    <th>Auxiliar 03</th>
                                    <th>Auxiliar 04</th>
                                    <th>Auxiliar 05</th>
                                    <th>Estatus</th>
                                    <th>Opciones</th>
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

@section('script')
    @include('Configuration.SystemPrinters.partials.table')
    <script>
        function reloadtable() {
            $('#table-printer').DataTable().ajax.reload();
        }
    </script>
@endsection
