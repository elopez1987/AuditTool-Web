<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-clear-{{$id}}">
    <form id="form-clear-{{$id}}" data-schedule-id="{{$id}}">
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;">Liberar registro.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-6 col-sm-12">
                            <span class="font-12">Libera el registro para programar nuevos recibos en el horario de descarga.</span>
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
    $(document).ready(function() {
        $('#form-clear-{{$id}}').on('submit', function(e) {
            e.preventDefault();
            
            var scheduleId = $(this).data('schedule-id');
            
            $.ajax({
                url: '{{url('appointmentscheduleclear')}}/' + scheduleId,
                type: 'PUT',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $('#btn-clear-{{$id}}').prop('disabled', true);
                },
                success: function(response) {
                    $('#modal-clear-{{$id}}').modal('hide');
                    
                    Swal.fire({
                        icon: 'success',
                        title: '\u00c9xito',
                        text: response.message || 'Registro desbloqueado con \u00e9xito',
                        timer: 3000,
                        showConfirmButton: false
                    });
                    
                    // Recargar DataTable
                    if (typeof reloadtable === 'function') {
                        $('#schedulle').DataTable().ajax.reload();
                    }
                },
                error: function(xhr) {
                    var errorMessage = 'Ocurri\u00f3 un error al desbloquear el registro';
                    
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
                    $('#btn-clear-{{$id}}').prop('disabled', false);
                }
            });
        });
    });
</script>
