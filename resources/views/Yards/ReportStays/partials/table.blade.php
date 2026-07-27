<script>
/**
 * Determina la clase CSS del badge de estadía.
 * El formato de entrada es "X m Y d HH:MM h/m" (meses, días, horas).
 */
function stayBadgeClass(stayStr) {
    if (!stayStr) { return 'stay-green'; }
    var match = stayStr.match(/(\d+)\s*m\s*(\d+)\s*d/);
    if (!match) { return 'stay-green'; }
    var totalDays = parseInt(match[1]) * 30 + parseInt(match[2]);
    if (totalDays > 60) { return 'stay-red'; }
    if (totalDays > 30) { return 'stay-amber'; }
    if (totalDays > 15) { return 'stay-yellow'; }
    return 'stay-green';
}

/** Formatea datetime de BD a "YYYY-MM-DD HH:mm". */
function fmtDT(val) {
    if (!val) { return '<span class="cell-dash">—</span>'; }
    var str = String(val).replace(' ', 'T');
    try {
        var d = new Date(str);
        if (isNaN(d.getTime())) { return val; }
        var z = function (n) { return n < 10 ? '0' + n : n; };
        return d.getFullYear() + '-' + z(d.getMonth() + 1) + '-' + z(d.getDate()) +
               ' ' + z(d.getHours()) + ':' + z(d.getMinutes());
    } catch (e) { return val; }
}

/*
 * Columnas mostradas (9) — deben coincidir exactamente con los 9 <th> del thead.
 * El servidor devuelve todos los campos; aquí usamos solo los relevantes.
 */
var oTable = $('#table-stays').DataTable({
    paging    : true,
    searching : false,   /* búsqueda controlada por formulario externo */
    ordering  : true,
    processing: true,
    serverSide: true,
    stateSave : false,
    ajax: {
        url : '{{ url("getDataStays") }}',
        data: function (d) {
            d.folionumber  = $('input[name=folionumber]').val();
            d.boxnumber    = $('input[name=boxnumber]').val();
            d.barcode      = $('input[name=barcode]').val();
            d.shipmentid   = $('input[name=shipmentid]').val();
            d.carrierid    = $('select[name=carrierid]').val();
            d.rasonid      = $('select[name=rasonid]').val();
            d.statusid     = $('select[name=statusid]').val();
            d.unitstatusid = $('select[name=unitstatusid]').val();
        }
    },
    columns: [
        /* Folio */
        { data: 'id',
          render: function (v) { return '<span class="stays-folio">' + v + '</span>'; } },
        /* Embarque */
        { data: 'shipmentid', defaultContent: '<span class="cell-dash">—</span>' },
        /* Línea de transporte */
        { data: 'carrier.name', name: 'carrier.name', defaultContent: '<span class="cell-dash">—</span>' },
        /* Placa de caja */
        { data: 'boxnumber', defaultContent: '<span class="cell-dash">—</span>' },
        /* Concepto de entrada */
        { data: 'reason.name', name: 'reason.name', defaultContent: '<span class="cell-dash">—</span>' },
        /* Estatus de folio */
        { data: 'status.name', name: 'status.name', defaultContent: '<span class="cell-dash">—</span>' },
        /* Estatus de unidad */
        { data: 'unitstatus.name', name: 'unitstatus.name', defaultContent: '<span class="cell-dash">—</span>' },
        /* Fecha de entrada */
        { data: 'entrydate', render: function (v) { return fmtDT(v); } },
        /* Estadía total — con badge de color */
        { data: 'stays',
          render: function (v) {
              if (!v) { return '<span class="cell-dash">—</span>'; }
              var cls = stayBadgeClass(v);
              return '<span class="stay-badge ' + cls + '">' + v + '</span>';
          }
        },
    ],
    dom: '<"d-none"B>rt<"d-flex justify-content-between align-items-center mt-3"<"text-muted small"i><p>>',
    pageLength: 25,
    lengthMenu: [[25, 50, 100, 200, -1], [25, 50, 100, 200, 'Todos']],
    buttons: [
        {
            extend    : 'excel',
            className : 'buttons-excel d-none',
            text      : 'Excel',
            title     : 'Estadías de patio',
            exportOptions: {
                /* Exportar también columnas ocultas del servidor si fuera necesario */
                columns: ':visible'
            }
        }
    ],
    language: {
        emptyTable  : 'No hay información',
        info        : 'Mostrando _START_ a _END_ de _TOTAL_ registros',
        infoEmpty   : 'Sin registros',
        infoFiltered: '(filtrado de _MAX_ total)',
        processing  : '<i class="fa fa-spinner fa-spin fa-2x"></i>',
        zeroRecords : 'Sin resultados encontrados',
        paginate: {
            first    : '«',
            last     : '»',
            next     : '›',
            previous : '‹',
        }
    },
});

/* El formulario dispara un redraw del DataTable */
$('#search').on('submit', function (e) {
    oTable.draw();
    e.preventDefault();
});
</script>
