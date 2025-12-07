/**
 * Inicializador global de DataTables con soporte serverSide
 * Pasa solo las opciones específicas (ajax, columns, columnDefs)
 */
function initDataTable(selector, extraOptions = {}) {

    const defaultOptions = {
        paging: true,
        searching: true,
        ordering: true,
        sScrollY: "450px",
        sScrollX: true,
        processing: true,
        serverSide: false, // Se activará desde extraOptions
        responsive: true,
        colReorder: true,
        stateSave: true,
        stateDuration: 0,

        dom: '<"d-flex justify-content-between align-items-center"Bfl>rt<"d-flex justify-content-between align-items-center p-2"ip>',

        lengthMenu: [
            [10, 30, 50, 100, 150, 200, 250, -1],
            [10, 30, 50, 100, 150, 200, 250, "Todos"]
        ],

        // Botones globales
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

        language: {
            decimal: "",
            emptyTable: "No hay información",
            loadingRecords: "Cargando...",
            processing: "Procesando...",
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ Entradas",
            zeroRecords: "Sin resultados encontrados",
            info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            infoEmpty: "Mostrando 0 a 0 de 0 Entradas",
            infoFiltered: "(Filtrado de _MAX_ total entradas)",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            }
        }
    };

    return $(selector).DataTable({
        ...defaultOptions,
        ...extraOptions
    });
}
