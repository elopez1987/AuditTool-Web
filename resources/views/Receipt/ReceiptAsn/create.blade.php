@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Captura de recibo
                </div>
                <div class="card-body">
                    <form id="receiptForm" method="POST" action="{{url('receiptasn')}}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('receiptasn.create')}}" id="btnSaveReceip">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('receiptasn.index')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        @include('Receipt.ReceiptAsn.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('css')
    <link rel="stylesheet"
          href="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/quill/dist/quill.snow.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/quill/dist/quill.min.js')}}"></script>
    <script src="{{asset('js/selects/warehouse-for-center.js')}}"></script>

    <script>
        $('.date').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD HH:mm',
            lang: 'es',
            weekStart: 1,
            cancelText: 'Cancelar'
        });
    </script>

    <script>
        $(document).ready(function () {
            $("#receiptForm").on("submit", function (e) {
                e.preventDefault();
                const $form = $(this);
                const $submitButton = $("#btnSaveReceip");
                const formData = new FormData(this);
                const url = $form.attr("action");
                $submitButton.prop("disabled", true);
                $submitButton.find("span").text(" Guardando...");
                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: "success",
                            title: "Éxito",
                            text: response.message || "Registro guardado exitosamente",
                            timer: 3000,
                            showConfirmButton: true
                        }).then(() => {
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            } else {
                                $form[0].reset();
                                $('.select2').val(null).trigger('change');
                                // window.location.href = "{{ route('receiptasn.index') }}";
                            }
                        });
                    },
                    error: function (xhr) {
                        let errorMessage = "Error al guardar el registro";
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON.errors) {
                                const errors = xhr.responseJSON.errors;
                                errorMessage = Object.values(errors).flat().join("<br>");
                            }
                        } else if (xhr.responseText) {
                            errorMessage = xhr.responseText;
                        }
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            html: errorMessage,
                            showConfirmButton: true
                        });
                    },
                    complete: function () {
                        $submitButton.prop("disabled", false);
                        $submitButton.find("span").text(" Guardar");
                    }
                });
            });
        });
    </script>

@endsection

