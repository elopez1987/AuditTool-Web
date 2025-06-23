@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Inventario de cajas fisicas vs sistema
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12">
                                @can('inventorybox.create')
                                    <a href="{{route('inventorybox.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
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
                                    <label for="folio">Número de inventario</label>
                                    <input type="text" class="form-control numeric" id="folio" name="folio"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
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
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <table id="table-boxes"
                                   class="table table-bordered table-striped table-condensed table-hover">
                                <thead class="bg-inverse">
                                <tr>
                                    <th class="text-white">C.Distribución</th>
                                    <th class="text-white">Folio de Inventario</th>
                                    <th class="text-white">Tipo</th>
                                    <th class="text-white">Descripción</th>
                                    <th class="text-white">F.Inicio</th>
                                    <th class="text-white">Cajas en patio</th>
                                    <th class="text-white">Total contadas</th>
                                    <th class="text-white">Estatus</th>
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
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    @include('Yards.InventoryBoxes.partials.table')
    <script>
        function reloadtable() {
            $('#table-boxes').DataTable().ajax.reload();
        }
    </script>
    <script>
        $("#statusid").change(function () {
            reloadtable();
        });
    </script>

    <script>
        function activeInventory($id) {
            Swal.fire({
                title: '¿Estas seguro de realizar el cambio de estatus?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#90a4ae',
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
                        url: '{!! route('activateInventory') !!}',
                        type: "POST",
                        dataType: 'json',
                        success: data => {
                            Swal.fire({
                                type: 'success',
                                title: `Cambio de estatus realizado con exito.`,
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
