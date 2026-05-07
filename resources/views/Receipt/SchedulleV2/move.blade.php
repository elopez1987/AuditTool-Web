<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-move-{{$id}}">
    <form id="form-clear-{{$id}}" data-schedule-id="{{$id}}">
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;">
                        Reprogramar registro.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <h5>Seleccione la fecha y bloque a mover la descarga.</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="workday-move-{{$id}}">Fecha de trabajo<span style="color: red">*</span></label>
                                <select name="workday" id="workday-move-{{$id}}" required class="form-control select2">
                                    <option value="" selected>Seleccione una opción</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="secuence">Fecha de trabajo<span style="color: red">*</span></label>
                                <select name="secuence" id="secuence-move-{{$id}}" required class="form-control select2" >
                                    <option value="" selected>Seleccione una opción</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm  waves-effect waves-light"
                            data-dismiss="modal">
                        <img src="{{asset('/pics/adcancelar.png')}}" alt="">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-outline-secondary btn-sm  waves-effect waves-light"
                            id="btn-clear-{{$id}}">
                        <img src="{{asset('/pics/adaceptar.png')}}" alt="">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Usar event delegation porque el modal se inyecta dinámicamente por DataTables
    $(document).on('show.bs.modal', '#modal-move-{{$id}}', function () {
        var select = $('#workday-move-{{$id}}');
        var selectSecuence = $('#secuence-move-{{$id}}');
        select.find('option:not(:first)').remove();
        selectSecuence.find('option:not(:first)').remove();

        $.ajax({
            url: '{{url('getdaysforperiods')}}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                $.each(data, function (index, item) {
                    select.append('<option value="' + item.workday + '">' + item.workday + '</option>');
                });
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudieron cargar las fechas disponibles.',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });

    $(document).on('change', '#workday-move-{{$id}}', function () {
        var workday = $(this).val();
        var selectSecuence = $('#secuence-move-{{$id}}');
        selectSecuence.find('option:not(:first)').remove();

        if (!workday) return;

        $.ajax({
            url: '{{url('getsecuenceforday')}}/' + workday,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                $.each(data, function (index, item) {
                    selectSecuence.append('<option value="' + item.id + '">' + item.secuence + '</option>');
                });
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudieron cargar las secuencias disponibles.',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });

    $(document).on('submit', '#form-clear-{{$id}}', function (e) {
        e.preventDefault();

        var form = $(this);
        var scheduleId = form.data('schedule-id');

        $.ajax({
            url: '{{url('movetosecuente')}}/' + scheduleId,
            type: 'PUT',
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $('#btn-clear-{{$id}}').prop('disabled', true);
            },
            success: function (response) {
                $('#modal-move-{{$id}}').modal('hide');

                Swal.fire({
                    icon: 'success',
                    title: '\u00c9xito',
                    text: response.message || 'Registro reprogramado correctamente.',
                    timer: 3000,
                    showConfirmButton: false
                });

                if (typeof reloadtable === 'function') {
                    reloadtable();
                }
            },
            error: function (xhr) {
                var errorMessage = 'Error al procesar la solicitud.';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                    confirmButtonText: 'Aceptar'
                });
            },
            complete: function () {
                $('#btn-clear-{{$id}}').prop('disabled', false);
            }
        });
    });
</script>
