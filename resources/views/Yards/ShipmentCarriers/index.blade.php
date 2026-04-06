@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Catalogo de transporte
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('shipmentcarriers.create')
                                    <a href="{{route('shipmentcarriers.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
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
                        <div class="card-input">
                            <div class="row">
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="name">Nombre</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                               aria-describedby="name">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="name">Vendor</label>
                                        <input type="text" class="form-control" id="carrierid" name="carrierid"
                                               aria-describedby="name">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="statusid">Estatus <span style="color: red">*</span></label>
                                        <select name="statusid" class="form-control select2">
                                            <option value="" selected>Selecciona una opción</option>
                                            @foreach($status as $model)
                                                <option value="{{$model->statusid}}"> {{$model->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-input">
                        <div class="row">
                            <div class="col-12">
                                <table id="table-trucks" class="table table-bordered  table-condensed table-hover">
                                    <thead>
                                    <tr>
                                        <th class="bg-inverse text-white">C.Distribución</th>
                                        <th class="bg-inverse text-white">Bodega</th>
                                        <th class="bg-inverse text-white">Nombre</th>
                                        <th class="bg-inverse text-white">Vendor</th>
                                        <th class="bg-inverse text-white">Auxiliar 01</th>
                                        <th class="bg-inverse text-white">Auxiliar 02</th>
                                        <th class="bg-inverse text-white">Auxiliar 03</th>
                                        <th class="bg-inverse text-white">Estatus</th>
                                        <th class="bg-inverse text-white">Opciones</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            @include('layouts.Partials.Errors')
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    @can('shipmentcarriers.importcarrier')
                        <a href="" data-target="#modal-upload" data-toggle="modal" data-placement="top">
                            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                <img src="{{asset('/ico/uploadcsv.png')}}" alt="" class="img-16">
                                <span class="text-button"> Importar catalogo </span>
                            </button>
                        </a>
                        @include('Yards.ShipmentCarriers.uploadfile')
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
    @include('Yards.ShipmentCarriers.partials.table')
    <script>
        function reloadtable() {
            $('#table-trucks').DataTable().ajax.reload();
        }
    </script>

    <script>
        function deletecarrier($id) {
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
                        url: '{!! route('deleteCarrier') !!}',
                        type: "POST",
                        dataType: 'json',
                        success: data => {
                            Swal.fire({
                                type: 'success',
                                title: `Carrier eliminada correctamente`,
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
