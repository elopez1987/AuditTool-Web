@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    pendientes de check list de entrada.
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a >
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('home')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="boxnumber">Placa de caja</label>
                                    <input type="text" class="form-control"  id="boxnumber" name="boxnumber"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="barcode">Código de caja</label>
                                    <input type="text" class="form-control"  id="barcode" name="barcode"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-lg-12 col-xs-12">
                            <table id="table-authorize"  class="table table-bordered  table-condensed">
                                <thead>
                                <tr>
                                    <th>C.Distribución</th>
                                    <th>Bodega</th>
                                    <th>Folio</th>
                                    <th>C.Barra</th>
                                    <th>Linea</th>
                                    <th>Operador</th>
                                    <th>Placa de Caja</th>
                                    <th>Placa de tracto</th>
                                    <th>Sello</th>
                                    <th>Fecha de registro</th>
                                    <th>Concepto de entrada</th>
                                    <th>Origen</th>
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
    @include('Yards.AuthorizeTransportEntry.partials.table')
    <script>
        function reloadtable() {
            $('#table-authorize').DataTable().ajax.reload();
        }
    </script>
@endsection
