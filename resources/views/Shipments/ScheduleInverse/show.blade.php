@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Detalle de Horario
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <a href="{{route('scheduleinverse.index')}}">
                                @include('buttons.back')
                            </a>
                        </div>
                    </div>
                    <hr class="mt-2 mb-2">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="name">Nombre <span style="color: red">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                       value="{{$schedulle->name}}" readonly
                                       onKeyUp=this.value=this.value.toUpperCase()
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-bordered table-condensed" id="table-detailschedulle">
                                <thead>
                                <tr>
                                    <th>Periodo</th>
                                    <th>Fecha de trabajo</th>
                                    <th>Bloque</th>
                                    <th>Folio de cita</th>
                                    <th>Cantidad de entrega</th>
                                    <th>Reservado por</th>
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
    @include('Shipments.ScheduleInverse.SchedulleDetail.table')
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    <script>
        function reloadtable() {
            $('#table-detailschedulle').DataTable().ajax.reload();
        }
    </script>
    <script>
        function changestatus($id) {
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
                        url: '{!! route('changestatusdetail') !!}',
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

