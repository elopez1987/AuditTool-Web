<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-addpriority-{{$receiptid}}">
    <form id="form-addpriority-{{$receiptid}}" data-receipt-id="{{$receiptid}}">
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;">Registrar Prioridad</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <p class="font-10">¿Desea marcar como prioridad de descarga?</p>
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
                            id="btn-addpriority-{{$receiptid}}">
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
        $('#form-addpriority-{{$receiptid}}').on('submit', function(e) {
            e.preventDefault();
            
            var receiptId = $(this).data('receipt-id');
            
            $.ajax({
                url: '{{url('addpriorityreceipt')}}/' + receiptId,
                type: 'PUT',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $('#btn-addpriority-{{$receiptid}}').prop('disabled', true);
                },
                success: function(response) {
                    $('#modal-addpriority-{{$receiptid}}').modal('hide');
                    
                    Swal.fire({
                        icon: 'success',
                        title: '\u00c9xito',
                        text: response.message || 'Prioridad asignada con \u00e9xito',
                        timer: 3000,
                        showConfirmButton: false
                    });
                    
                    // Recargar DataTable
                    if (typeof reloadtable === 'function') {
                        reloadtable();
                    }
                },
                error: function(xhr) {
                    var errorMessage = 'Ocurri\u00f3 un error al asignar la prioridad';
                    
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
                    $('#btn-addpriority-{{$receiptid}}').prop('disabled', false);
                }
            });
        });
    });
</script>
