<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-blocked-{{$id}}">
    <form id="form-blocked-{{$id}}" data-schedule-id="{{$id}}">
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;">Bloquear registro.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-6 col-sm-12">
                            <span class="font-12">Bloquea el registro para reservar el espacio, evitando que sea asignado o utilizado por otro usuario mientras esté activo el bloqueo.</span>
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
                            id="btn-blocked-{{$id}}">
                        <img src="{{asset('/pics/adaceptar.png')}}" alt="">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#form-blocked-{{$id}}').on('submit', function(e) {
            e.preventDefault();
            
            var scheduleId = $(this).data('schedule-id');
            
            $.ajax({
                url: '{{url('appointmentschedule')}}/' + scheduleId,
                type: 'PUT',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $('#btn-blocked-{{$id}}').prop('disabled', true);
                },
                success: function(response) {
                    $('#modal-blocked-{{$id}}').modal('hide');
                    
                    Swal.fire({
                        icon: 'success',
                        title: '\u00c9xito',
                        text: response.message || 'Registro bloqueado con \u00e9xito',
                        timer: 3000,
                        showConfirmButton: false
                    });
                    
                    // Recargar DataTable
                    if (typeof reloadtable === 'function') {
                        reloadtable();
                    }
                },
                error: function(xhr) {
                    var errorMessage = 'Ocurri\u00f3 un error al bloquear el registro';
                    
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
                complete: function() {
                    $('#btn-blocked-{{$id}}').prop('disabled', false);
                }
            });
        });
    });
</script>
