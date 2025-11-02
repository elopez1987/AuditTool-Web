@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Asignar Tracto Camion a Remolque.
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('home')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-1">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="folionumber">Folio</label>
                                    <input type="text" class="form-control numeric"  id="folionumber" name="folionumber"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
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
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="carrierid">Linea de transporte<span style="color: red">*</span></label>
                                    <select name="carrierid" class="form-control select2">
                                        <option value="" selected>Selecciona una opción</option>
                                        @foreach($carriers as $model)
                                            <option value="{{$model->id}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-12">
                            <table id="table-trucksbox" class="table table-bordered  table-condensed table-hover table-uppercase">
                                <thead>
                                <tr>
                                    <th class="text-white">Bodega</th>
                                    <th class="text-white">Folio</th>
                                    <th class="text-white">C.Barra</th>
                                    <th class="text-white">Linea de transporte</th>
                                    <th class="text-white">Placas de caja</th>
                                    <th class="text-white">C.Barra</th>
                                    <th class="text-white">Placas de tracto</th>
                                    <th class="text-white">Nombre del operador</th>
                                    <th class="text-white">Celular</th>
                                    <th class="text-white">Opciones</th>
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

@endsection


@section('script')
    @include('Yards.AssignTrucks.partials.table')
    <script>
        function reloadtable() {
            $('#table-trucksbox').DataTable().ajax.reload();
        }
    </script>
@endsection
