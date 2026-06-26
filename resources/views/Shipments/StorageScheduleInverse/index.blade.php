@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Logistica Inversa
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('storagescheduleinverse.create')
                                    <a href="{{route('storagescheduleinverse.create')}}">
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
                                    <label for="shipmentid">Embarque</label>
                                    <input type="text" class="form-control" id="shipmentid" name="shipmentid"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusid">Estatus</label>
                                    <select name="statusid" class="form-control select2">
                                        <option value="" selected>Seleccione un opcion.</option>
                                        @foreach($status as $model)
                                            <option value="{{$model->statusid}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <table id="table-inverse" class="table table-bordered table-hover table-condensed" style="width:100%">
                                <thead>
                                <tr>
                                    <th>C.Distribución</th>
                                    <th>Bodega</th>
                                    <th>Embarque</th>
                                    <th>Cliente</th>
                                    <th>Linea de transporte</th>
                                    <th>Fecha de reporte</th>
                                    <th>Cita de entrega</th>
                                    <th>Folio Patios</th>
                                    <th>Fecha de llegada</th>
                                    <th>Estatus</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    @can('importrequestzare')
                        <a href="" data-target="#modal-upload" data-toggle="modal" data-placement="top">
                            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                <i class="lni lni-upload text-trax font-22 mr-2"></i>
                                <span style="font-size: 11px;"> Solicitud Zare </span>
                            </button>
                        </a>
                        @include('Shipments.StorageScheduleInverse.Imports.requestzare')
                    @endcan
                    @can('importresponsezare')
                        <a href="" data-target="#modal-response" data-toggle="modal" data-placement="top">
                            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                <i class="lni lni-upload text-trax font-22 mr-2"></i>
                                <span style="font-size: 11px;"> Recepción Zare </span>
                            </button>
                        </a>
                        @include('Shipments.StorageScheduleInverse.Imports.responsezare')
                    @endcan
                    @can('importrequestne')
                        <a href="" data-target="#modal-requestne" data-toggle="modal" data-placement="top">
                            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                <i class="lni lni-upload text-trax font-22 mr-2"></i>
                                <span style="font-size: 11px;"> Solicitud NE </span>
                            </button>
                        </a>
                        @include('Shipments.StorageScheduleInverse.Imports.requestne')
                    @endcan
                    @can('importresponsene')
                        <a href="" data-target="#modal-responsene" data-toggle="modal" data-placement="top">
                            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                <i class="lni lni-upload text-trax font-22 mr-2"></i>
                                <span style="font-size: 11px;"> Recepción de  NE </span>
                            </button>
                        </a>
                        @include('Shipments.StorageScheduleInverse.Imports.responsene')
                    @endcan
                    @can('importrequestnc')
                        <a href="" data-target="#modal-requestnc" data-toggle="modal" data-placement="top">
                            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                <i class="lni lni-upload text-trax font-22 mr-2"></i>
                                <span style="font-size: 11px;"> Solicitud de  NC </span>
                            </button>
                        </a>
                        @include('Shipments.StorageScheduleInverse.Imports.requestnc')
                    @endcan
                    @can('importresponsenc')
                        <a href="" data-target="#modal-responsenc" data-toggle="modal" data-placement="top">
                            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                <i class="lni lni-upload text-trax font-22 mr-2"></i>
                                <span style="font-size: 11px;"> Respuesta de  NC </span>
                            </button>
                        </a>
                        @include('Shipments.StorageScheduleInverse.Imports.responsenc')
                    @endcan
                    @can('importregisterwms')
                        <a href="" data-target="#modal-registerwms" data-toggle="modal" data-placement="top">
                            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                <i class="lni lni-upload text-trax font-22 mr-2"></i>
                                <span style="font-size: 11px;"> Registrar Entrega </span>
                            </button>
                        </a>
                        @include('Shipments.StorageScheduleInverse.Imports.registerwms')
                    @endcan
                    @can('importresponseinventory')
                        <a href="" data-target="#modal-responseinventory" data-toggle="modal" data-placement="top">
                            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                <i class="lni lni-upload text-trax font-22 mr-2"></i>
                                <span style="font-size: 11px;"> Respuestas de inventario </span>
                            </button>
                        </a>
                        @include('Shipments.StorageScheduleInverse.Imports.responseinventory')
                    @endcan
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('css')
@endsection

@section('script')
    @include('Shipments.StorageScheduleInverse.partials.table')
    <script>
        function reloadtable() {
            $('#table-inverse').DataTable().ajax.reload();
        }
    </script>

    <script>
        function closeInverse($id) {
            Swal.fire({
                title: '¿Estas seguro?',
                text: "Cambiar estatus de cerrar el registro!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, terminar el proceso!'
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
                        url: '{!! route('closeprocessinverse') !!}',
                        type: "POST",
                        dataType: 'json',
                        success: data => {
                            Swal.fire({
                                type: 'success',
                                title: `Evento cerrado correctamente`,
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
