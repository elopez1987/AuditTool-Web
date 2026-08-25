/**
 * Inventory CardDetail - DataTable + Delete logic
 */

$(document).ready(function () {

    // ── DataTable ────────────────────────────────────────────────────────────
    var oTable = initDataTable('#table-cards', {
        serverSide: true,
        searching: false,
        ajax: {
            url: CardDetail.ajaxUrl,
            data: function (d) {
                d.name = $('input[name=name]').val();
                d.cardname = $('input[name=cardname]').val();
            }
        },
        columns: [
            {data: 'id'},
            {data: 'pasillo',         name: 'pasillo'},
            {data: 'cedula',          name: 'cedula'},
            {data: 'user_count01',    name: 'user_count01'},
            {data: 'user_count02',    name: 'user_count02'},
            {
                data: 'statusid_count01', name: 'statusid_count01',
                createdCell: function (td,rowData) {
                    $(td).css({
                        'background-color': (rowData.color_count01),
                        'color': 'black'
                    });
                }
            },
            {
                data: 'statusid_count02', name: 'statusid_count02',
                createdCell: function (td, rowData) {
                    $(td).css({
                        'background-color': (rowData.color_count02),
                        'color': 'black'
                    });
                }
            },
            {data: 'btn', orderable: false, searchable: false}
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end">>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        buttons: [
            {
                "extend": "colvis",
                "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt=''><span  style='font-size: 11px;'> Mostar/Ocultar</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
                columns: ':not(:first):not(:last)'
            },
            {
                "extend": "csv",
                "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16'><span  style='font-size: 11px;'> Csv</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
            },
            {
                "extend": "excel",
                "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt=''><span  style='font-size: 11px;'> Excel</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
            }
        ],
        "language": {
            "url": "{{asset('/js/spanish.json')}}"
        }
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

    // ── Limpiar usuario 01 ───────────────────────────────────────────────────────
    $(document).on('click', '.btn-clear01', function () {
        var id = $(this).data('id');

        Swal.fire({
            title: '¿Desasignar usuario de conteo 1?',
            text: 'Esta acción eliminará la asignación del usuario 1.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, limpiar',
            cancelButtonText: 'Cancelar'
        }).then(function (result) {
            if (!result.isConfirmed) return;

            $.ajax({
                url: CardDetail.clear01Url + '/' + id,
                type: 'PUT',
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

    // ── Limpiar usuario 02 ───────────────────────────────────────────────────────
    $(document).on('click', '.btn-clear02', function () {
        var id = $(this).data('id');

        Swal.fire({
            title: '¿Desasignar usuario de conteo 2?',
            text: 'Esta acción eliminará la asignación del usuario 2.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, limpiar',
            cancelButtonText: 'Cancelar'
        }).then(function (result) {
            if (!result.isConfirmed) return;

            $.ajax({
                url: CardDetail.clear02Url + '/' + id,
                type: 'PUT',
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
