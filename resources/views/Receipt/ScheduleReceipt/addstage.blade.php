@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar cortina anticipada.
                </div>
                <div class="card-body">

                    <form method="POST" action="{{url('receiptasnassingstage/' . $receiptasn->id)}}">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="row">
                                    <div class="col-lg-10">
                                        <a href="">
                                            @include('buttons.save')
                                        </a>
                                        <a href="{{url('schedulereceipt')}}">
                                            @include('buttons.back')
                                        </a>
                                    </div>
                                </div>
                                <hr class="mt-2">
                                <div class="card-input">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h4 class="card-title"> Cortinas disponibles </h4>
                                            <select multiple="multiple" size="15" class="duallistbox-with-filter font-12"
                                                    name="locationstage[]" required >
                                                @foreach($locations as $location)
                                                    <option value="{{$location->id}}" class="font-12"> {{$location->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <hr class="mt-3">

                    <div class="row">
                        <div class="col-lg-6">
                            <table class="table table-condensed table-bordered tablegeneral">
                                <thead>
                                    <tr>
                                        <th>Cortina</th>
                                        <th>Eliminar asignación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stages as $stage)
                                        <tr>
                                            <td>{{$stage->location->name}}</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-info"
                                                        onclick="deleteitem( {!! $stage->id !!} )">
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
    <link rel="stylesheet"
          href="{{asset('theme/assets/libs/bootstrap-duallistbox/dist/bootstrap-duallistbox.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('theme/assets/libs/bootstrap-duallistbox/dist/jquery.bootstrap-duallistbox.min.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    @include('layouts.Partials.DataTable')

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
        function deleteitem($id) {
            Swal.fire({
                title: '¿Estas seguro?',
                text: "El siguiente evento eliminara el registro",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar cortina!'
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
                        url: '{!! route('deletestagereceipt') !!}',
                        type: "POST",
                        dataType: 'json',
                        success: data => {
                            Swal.fire({
                                type: 'success',
                                title: `Evento procesado correctamente`,
                                // text: ` Fecha de cita : ${data.value02} `,
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
