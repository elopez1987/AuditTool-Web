@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Check list de transporte
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('storagechecklists.create')
                                    <a href="{{route('storagechecklists.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-1">
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
                                    <input type="text" class="form-control" id="name" name="name"
                                           aria-describedby="name">
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <table id="table-checklist" class="table table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th>C.Distribución</th>
                                    <th>Bodega</th>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Concepto de entrada</th>
                                    <th>Tipo</th>
                                    <th>Origen</th>
                                    <th>Tipo de check list</th>
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

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    @include('Yards.StorageCheckLists.partials.table')
    <script>
        function reloadtable() {
            $('#table-checklist').DataTable().ajax.reload();
        }
    </script>
    <script>
        $("#zoneid,#statusid,#warehouseid").change(function () {
            reloadtable();
        });
    </script>
    <script>
        function deletechecklist($id) {
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
                        url: '{!! route('deleteChecklists') !!}',
                        type: "POST",
                        dataType: 'json',
                        success: data => {
                            Swal.fire({
                                type: 'success',
                                title: `Check list eliminado correctamente!`,
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
