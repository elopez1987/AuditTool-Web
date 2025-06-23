@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Relacionar Stages con cortina
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <form method="POST" action="{{url('storagedoorstage/' . $location->id)}}">
                                {{ method_field('PUT') }}
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <a href="">
                                            @include('buttons.save')
                                        </a>
                                        <a href="{{route('storagedoorstage.index')}}">
                                            @include('buttons.back')
                                        </a>
                                    </div>
                                </div>
                                <hr class="mt-2">
                                @include('Shipments.StorageDoorStages.partials.formEdit')
                            </form>
                        </div>
                        <div class="col-lg-6 mt-5">
                            <table class="table table-condensed table-bordered table-striped table-hover tablegeneral">
                                <thead>
                                    <tr>
                                        <th>Precarga</th>
                                        <th>Cortina</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stagesForDoor as $model)
                                        <tr>
                                            <td>{{$model->stage}}</td>
                                            <td>{{$model->door}}</td>
                                            <td>
                                                <a data-toggle="tooltip" data-placement="top"
                                                   title="Eliminar registro"
                                                   onclick="deleteDetailStage( {!! $model->id !!} )">
                                                    <img src="{{asset('/ico/adcancelar.png')}}" alt=""
                                                         class="img-16 ml-1">
                                                </a>
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
    <link rel="stylesheet"
          href="{{asset('theme/assets/libs/bootstrap-duallistbox/dist/bootstrap-duallistbox.min.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection

@section('script')
    @include('layouts.Partials.DataTable')
    <script
        src="{{asset('theme/assets/libs/bootstrap-duallistbox/dist/jquery.bootstrap-duallistbox.min.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>

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
        function deleteDetailStage($id) {
            Swal.fire({
                title: '¿Estas seguro?',
                text: "El siguiente eliminara el Stage relacionado a la cortina.!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar stage!'
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
                        url: '{!! route('deletestageofdoor') !!}',
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

