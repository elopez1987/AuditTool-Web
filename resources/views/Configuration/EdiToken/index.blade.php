@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Configuración de Web Service
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('editokens.create')
                                    <a href="{{route('editokens.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reload')
                                </a>
                                <a href="{{route('menuconfiguration')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                    </form>
                    <div class="card-input">
                        <div class="row">
                            <div class="col-lg-12 col-xs-12">
                                <table id="table-editoken"
                                       class="table table-bordered table-striped table-condensed table-hover">
                                    <thead>
                                    <tr>
                                        <th class="bg-inverse text-white">Url</th>
                                        <th class="bg-inverse text-white">Cliente</th>
                                        <th class="bg-inverse text-white">Token</th>
                                        <th class="bg-inverse text-white">Tiempo de respuesta</th>
                                        <th class="bg-inverse text-white">Tipo de token</th>
                                        <th class="bg-inverse text-white">Tipo de servicio</th>
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
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    @include('Configuration.EdiToken.partials.table')
    <script>
        function reloadtable() {
            $('#table-editoken').DataTable().ajax.reload();
        }
    </script>
    <script>
        function deletetoken($id) {
            Swal.fire({
                title: '¿Estas seguro?',
                text: "Confirme la acción de eliminar el registro!",
                type: 'warning',
                showCancelButton: true,
                // confirmButtonColor: '#f44336',
                cancelButtonColor: '#cfd8dc',
                confirmButtonText: 'Eliminar'
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
                        url: '{!! route('destroytoken') !!}',
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
