/**
 * Inventory CardDetail - DataTable + Delete logic
 */

$(document).ready(function () {

    // ── DataTable ────────────────────────────────────────────────────────────
    var oTable = initDataTable('#table-cards', {
        serverSide: true,
        ajax: {
            url: CardDetail.ajaxUrl,
            data: function (d) {
                d.name = $('input[name=name]').val();
            }
        },
        columns: [
            {data: 'id'},
            {data: 'pasillo',         name: 'pasillo'},
            {data: 'cedula',          name: 'cedula'},
            {data: 'user_count01',    name: 'user_count01'},
            {data: 'user_count02',    name: 'user_count02'},
            {data: 'statusid_count01',name: 'statusid_count01'},
            {data: 'statusid_count02',name: 'statusid_count02'},
            {data: 'btn', orderable: false, searchable: false}
        ]
    });

    // ── Buscar al enviar el formulario ────────────────────────────────────────
    $('#search').on('submit', function (e) {
        oTable.draw();
        e.preventDefault();
    });

    // ── Recargar tabla ────────────────────────────────────────────────────────
    window.reloadtable = function () {
        oTable.ajax.reload();
    };

    // ── Eliminar cédula ───────────────────────────────────────────────────────
    $(document).on('click', '.btn-delete', function () {
        var id = $(this).data('id');

        Swal.fire({
            title: '¿Eliminar cédula?',
            text: 'Esta acción eliminará también las ubicaciones asociadas y no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(function (result) {
            if (!result.isConfirmed) return;

            $.ajax({
                url: CardDetail.deleteUrl + '/' + id,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': CardDetail.csrfToken
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: response.message,
                            timer: 1800,
                            showConfirmButton: false
                        }).then(function () {
                            oTable.ajax.reload(null, false);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function (xhr) {
                    var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error al eliminar el registro.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: msg
                    });
                }
            });
        });
    });

});
