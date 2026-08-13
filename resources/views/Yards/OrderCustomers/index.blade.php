@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Catalogo de clientes
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('ordercustomers.create')
                                    <a href="{{route('ordercustomers.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                   @include('buttons.reloadajax')
                                </a>
                                    <a href="{{route('menushipment')}}">
                                        @include('buttons.cancel')
                                    </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="warehouseid">Bodega</label>
                                    <select name="warehouseid" id='warehouseid'  class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($warehouses as $model)
                                            <option value="{{$model->warehouseid}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control"  id="name" name="name">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="shiptoid">Embarcar a</label>
                                    <input type="text" class="form-control"  id="shiptoid" name="shiptoid">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusid">Estatus</label>
                                    <select name="statusid" id='statusid' class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($status as $model)
                                            <option value="{{$model->statusid}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table-trucks" class="table table-bordered  table-condensed" width="100%">
                                <thead>
                                <tr>
                                    <th>C.Distribución</th>
                                    <th>Bodega</th>
                                    <th>Nombre</th>
                                    <th>Embarcar a</th>
                                    <th>Ciudad</th>
                                    <th>T.Tránsito</th>
                                    <th>Estatus</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            @include('layouts.Partials.Errors')
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    @can('ordercustomers.importcustomer')
                        <a href="" data-target="#modal-upload" data-toggle="modal" data-placement="top">
                            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                <img src="{{asset('/ico/uploadcsv.png')}}" alt="" class="img-16">
                                <span style="font-size: 11px;"> Importar catalogo </span>
                            </button>
                        </a>
                        @include('Yards.OrderCustomers.uploadfile')
                    @endcan
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    @include('Yards.OrderCustomers.partials.table')
    <script>
        function reloadtable() {
            $('#table-trucks').DataTable().ajax.reload();
        }
    </script>
    <script>
        $("#statusid,#warehouseid").change(function () {
            reloadtable();
        });
        /* Recarga automatica al salir de los campos de texto, sin esperar a
           que se presione "Buscar". */
        $("#name,#shiptoid").on('blur', function () {
            reloadtable();
        });
    </script>
    <script>
        function deletecustomer($id) {
            Swal.fire({
                title: '¿Estas seguro?',
                text: "Eliminar el registro!",
                type: 'warning',
                showCancelButton: true,
                // confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                confirmButtonColor: '#66bb6a',
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        data: {
                            id: $id,
                        },
                        url: '{!! route('deleteCustomer') !!}',
                        type: "POST",
                        dataType: 'json',
                        success: data => {
                            Swal.fire({
                                type: 'success',
                                title: `Origen eliminado correctamente!`,
                                width: 350,
                            })
                            reloadtable();
                        },
                    })
                }
            })
        }
    </script>
@endsection
