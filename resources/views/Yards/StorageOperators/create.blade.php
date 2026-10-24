@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar Operador
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="POST" action="{{url('storageoperators')}}" enctype="multipart/form-data">
                                @csrf
                                <hr class="b-3">
                                @include('Yards.StorageOperators.partials.formCreate')
                                <hr class="b-3">
                                <div class="row">
                                    <div class="col-12">
                                        <a href="{{route('storageoperators.create')}}">
                                            @include('buttons.save')
                                        </a>
                                        <a href="{{route('storageoperators.index')}}">
                                            @include('buttons.back')
                                        </a>
                                    </div>
                                </div>
                            </form>
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
    <script>
        $('#searchOperatorInRC').click(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let licencerc = $('#licencerc').val();
            let rfcoperator = $('#rfcoperator').val();
            $.ajax({
                url: "{{url('searchStatusOfOperator')}}",
                type: "POST",
                data: {
                    licencerc: licencerc,
                    rfcoperator: rfcoperator,
                },
                success: function (result) {
                    $("#name").val(result.operator.operatorname);
                    $("#statusoperator").val(result.operator.statusoperator);
                    $("#carriername").val(result.operator.carriername);
                    $("#rfccarrier").val(result.operator.rfccarrier);
                    $("#rfcoperator").val(result.operator.rfcoperator);
                    $("#drivernumber").val(result.operator.credential);
                    document.getElementById('validateoperator').checked = true;
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        type: 'error',
                        title: `Operador no encontrado`,
                        width: 350,
                    })
                }
            });
        });
    </script>
    <script>
        document.getElementById('clearInputs').addEventListener('click', function () {
            document.getElementById('operatorname').value = '';
            document.getElementById('statusoperator').value = '';
            document.getElementById('carriername').value = '';
            document.getElementById('rfccarrier').value = '';
            document.getElementById('rfcoperator').value = '';
            document.getElementById('licencerc').value = '';
            document.getElementById('validateoperator').checked = false;
        });
    </script>
@endsection

