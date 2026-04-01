@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                   Asignar operadores por tipo de equipos
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('storageassignlifttrucks/' . $type->statusid)}}">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('storageassignlifttrucks.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-2">
                        @include('Maintenance.StorageAssignLiftTrucks.partials.formEdit')
                    </form>
                    <hr class="mt-2">
                    <div class="row mt-4">
                        <div class="col-lg-6">
                            <table class="table table-condensed  table-bordered tablegeneral">
                                <thead>
                                <tr>
                                    <th>Montcarguista</th>
                                    <th>Creado por</th>
                                    <th>Fecha de asignación</th>
                                    <th>Eliminar</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($userAssigns as $truck)
                                    <tr>
                                        <td>{{$truck->usertruck ? $truck->usertruck->name : ''}}</td>
                                        <td>{{$truck->assigntruck ? $truck->assigntruck->name : ''}}</td>
                                        <td>{{$truck->created_at}}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-info"
                                                    onclick="deleteUserTruck( {!! $truck->id !!} )">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('theme/assets/libs/bootstrap-duallistbox/dist/bootstrap-duallistbox.min.css')}}">
@endsection


@section('script')
    @include('layouts.Partials.DataTable')
    <script src="{{asset('theme/assets/libs/bootstrap-duallistbox/dist/jquery.bootstrap-duallistbox.min.js')}}"></script>

    <script>
        $('.duallistbox-with-filter').bootstrapDualListbox({
            nonSelectedListLabel: 'No hay registros selecionados:',
            selectedListLabel: 'Seleccionado',
            preserveSelectionOnMove: 'Mover',
            moveOnSelect: false,
        });
        $(function () {
            $('.moveall i').removeClass().addClass('fas fa-angle-right');
            $('.removeall i').removeClass().addClass('fas fa-angle-left');
            $('.move i').removeClass().addClass('fas fa-angle-right');
            $('.remove i').removeClass().addClass('fas fa-angle-left');
        });
    </script>

    <script>
        function deleteUserTruck($id) {
            Swal.fire({
                title: '¿Estas seguro?',
                text: "El siguiente evento eliminara al montacarguista de este equipo!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar asignación.!'
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
                        url: '{!! route('deleteUserAssingTruck') !!}',
                        type: "POST",
                        dataType: 'json',
                        success: data => {
                            Swal.fire({
                                type: 'success',
                                title: `Evento procesado correctamente`,
                                width: 350,
                            })
                            setTimeout(function () {
                                window.location.reload()
                            }, 2000)
                        },
                    })
                }
            })
        }
    </script>
@endsection

