@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Catalogo de etiquetas
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('inventorylabels.create')
                                    <a href="{{route('inventorylabels.create')}}">
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
                        <div class="card-input">
                            <div class="row">
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <label for="description">Etiqueta <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" required id="description" name="description" maxlength="15"
                                           aria-describedby="description" onKeyUp=this.value=this.value.toUpperCase();
                                           value="{{old('description')}}">
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="card-input">
                        <div class="row">
                            <div class="col-lg-12 col-xs-12">
                                <table id="table-labelysystem" class="table table-bordered table-condensed">
                                    <thead>
                                    <tr>
                                        <th>C.Distribución</th>
                                        <th>Nombre</th>
                                        <th>Etiqueta</th>
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
    </div>

@endsection

@section('script')
    @include('Configuration.InventoryLabels.partials.table')
    <script>
        function reloadtable() {
            $('#table-labelysystem').DataTable().ajax.reload();
        }
    </script>
@endsection
