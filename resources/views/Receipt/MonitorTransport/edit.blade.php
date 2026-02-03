@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Captura de datos
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('monitor-transport-receipt/' . $receipt->id . '/create-qr')}}"
                          enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row mb-4">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('monitortransportreceipt')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="m-b-5">
                        @include('Receipt.MonitorTransport.partials.formEdit')
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            @include('layouts.Partials.Errors')
        </div>
    </div>
@endsection

@section('css')

@endsection

@section('script')


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
                    $("#operatorname").val(result.operator.operatorname);
                    $("#statusoperator").val(result.operator.statusoperator);
                    $("#carriername").val(result.operator.carriername);
                    $("#rfccarrier").val(result.operator.rfccarrier);
                    $("#rfcoperator").val(result.operator.rfcoperator);
                    $("#licencerc").val(result.operator.credential);
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

