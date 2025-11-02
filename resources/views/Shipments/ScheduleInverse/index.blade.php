@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Horarios
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('scheduleinverse.create')
                                    <a href="{{route('scheduleinverse.create')}}">
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
                        <hr class="m-t-1">
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <table id="table-scheduleinverse"
                                   class="table table-bordered table-hover table-condensed">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">C.Distribución</th>
                                    <th class="bg-inverse text-white">Bodega</th>
                                    <th class="bg-inverse text-white">Nombre</th>
                                    <th class="bg-inverse text-white">Fecha de inicio</th>
                                    <th class="bg-inverse text-white">Fecha de final</th>
                                    <th class="bg-inverse text-white">Usuario</th>
                                    <th class="bg-inverse text-white">Estatus</th>
                                    <th class="bg-inverse text-white">Opciones</th>
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
    @include('Shipments.ScheduleInverse.partials.table')
    <script>
        function reloadtable() {
            $('#table-scheduleinverse').DataTable().ajax.reload();
        }
    </script>
    <script>
        function activateschedulle($id) {
            Swal.fire({
                title: '¿Estas seguro?',
                text: "Cambiar estatus del registro!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, activar el registro!'
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
                        url: '{!! route('activateschedulle') !!}',
                        type: "POST",
                        dataType: 'json',
                        success: data => {
                            Swal.fire({
                                type: 'success',
                                title: `${data.Message}`,
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
