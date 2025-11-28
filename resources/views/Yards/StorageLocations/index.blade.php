@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Catalogo de ubicaciones
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('storagelocations.create')
                                    <a href="{{route('storagelocations.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                @include('buttons.reloadajax')
                                <a href="{{route('home')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="warehouseid">Bodega</label>
                                    <select name="warehouseid" id='warehouseid' class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($warehouses as $model)
                                            <option value="{{$model->warehouseid}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="zoneid">Zonas<span style="color: red">*</span></label>
                                    <select name="zoneid" id='zoneid' class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name"
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
                    <div class="row mt-4">
                        <div class="col-12">
                            <table id="table-locations" class="table table-bordered  table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">C.Distribución</th>
                                    <th class="bg-inverse text-white">Bodega</th>
                                    <th class="bg-inverse text-white">Zona</th>
                                    <th class="bg-inverse text-white">Nombre</th>
                                    <th class="bg-inverse text-white">Ubicacion WMS</th>
                                    <th class="bg-inverse text-white">C.Barra</th>
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
                    <hr class="mb-3">
                    <div class="row">
                        @foreach($locations as $location)
                            <div class="col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h2>{{$location->total_locations}}</h2>
                                                <h6>{{$location->zone->name}}</h6>
                                            </div>
                                            <div class="ml-auto">
                                                <span class="display-6"><i class="ti-location-pin"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                    @can('storagelocations.importlocation')
                        <a href="" data-target="#modal-upload" data-toggle="modal" data-placement="top">
                            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                <img src="{{asset('/ico/uploadcsv.png')}}" alt="" class="img-16">
                                <span style="font-size: 11px;"> Importar catalogo </span>
                            </button>
                        </a>
                        @include('Yards.StorageLocations.uploadfile')
                    @endcan
                    @can('optimizelocations')
                        <a href="{{route('optimizelocations')}}">
                            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                <img src="{{asset('/ico/code.png')}}" alt="" class="img-16">
                                <span style="font-size: 11px;"> Optimizar Ubicaciones</span>
                            </button>
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    @include('Yards.StorageLocations.partials.table')
    <script>
        function reloadtable() {
            $('#table-locations').DataTable().ajax.reload();
        }
    </script>
    <script>
        $("#zoneid,#statusid,#warehouseid").change(function () {
            reloadtable();
        });
    </script>

    <script type="module">
        const moduleUrl = "{{ asset('js/selects/zone-for-warehouse.js') }}";
        const baseUrl = "{{ url('') }}";
        const {initializeZoneLookup} = await import(moduleUrl);
        initializeZoneLookup("warehouseid", "zoneid", baseUrl);
    </script>

    <script>
        // SweetAlert2 delete handler
        $(document).on('click', '.btn-delete-location', function (e) {
            e.preventDefault();
            const locationId = $(this).data('id');

            Swal.fire({
                title: '¿Estás seguro?',
                text: '¿Deseas eliminar este registro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Eliminando...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Send AJAX request
                    $.ajax({
                        url: `{{url('storagelocations')}}/${locationId}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{csrf_token()}}'
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Eliminado!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            // Reload DataTable
                            $('#table-locations').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            const message = xhr.responseJSON?.message || 'Error al eliminar el registro';
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: message
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
