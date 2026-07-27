<script>
/* ── Global helpers (usados también en la vista de tarjetas) ── */

/**
 * Formatea un datetime string de BD a "YYYY-MM-DD HH:mm".
 * Devuelve un guión si el valor es nulo/vacío.
 */
function fmtDateTime(val) {
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

/** Número de días transcurridos desde entrydate hasta hoy. */
function stayDays(entrydate) {
    if (!entrydate) { return 0; }
    var str = String(entrydate).replace(' ', 'T');
    return Math.floor((new Date() - new Date(str)) / 86400000);
}

/** Clase CSS del badge según los días de estadía. */
function stayBadgeClass(days) {
    if (days > 90) { return 'stay-red'; }
    if (days > 60) { return 'stay-orange'; }
    if (days > 30) { return 'stay-yellow'; }
    if (days < 15) { return 'stay-green'; }
    return 'stay-green';
}

/*
 * IMPORTANTE: filterSearchText, filterRazon, filterStayDays y filterEntryDate
 * son variables globales declaradas en index.blade.php antes de $(document).ready().
 * El callback ajax.data las lee directamente sin necesidad de typeof.
 */
var oTable = $('#table-monitorbox').DataTable({
    paging    : true,
    searching : false,
    ordering  : true,
    processing: true,
    serverSide: true,
    stateSave : false,
    ajax: {
        url : '{{ url("getDataStockBox") }}',
        data: function (d) {
            d.search_text   = filterSearchText;
            d.razonstatusid = filterRazon;
            d.stay_days     = filterStayDays;
            d.entry_date    = filterEntryDate;
        }
    },
    /*
     * Solo 10 columnas visibles — debe coincidir exactamente
     * con los 10 <th> del <thead> en index.blade.php.
     */
    columns: [
        { data: 'id',
          render: function (v) { return '<span class="folio-num">' + v + '</span>'; } },
        { data: 'carrier.name',        name: 'carrier.name',    defaultContent: '—' },
        { data: 'boxnumber',        name: 'boxnumber',    defaultContent: '—' },
        { data: 'event.name',          name: 'event.name',      defaultContent: '—' },
        { data: 'entrydate',           render: function (v) { return fmtDateTime(v); } },
        { data: 'deliverdate',         render: function (v) { return fmtDateTime(v); } },
        { data: 'ensnare',             render: function (v) { return fmtDateTime(v); } },
        { data: 'keydate',             render: function (v) { return fmtDateTime(v); } },
        { data: 'startdate',           render: function (v) { return fmtDateTime(v); } },
        { data: 'finishdate',           render: function (v) { return fmtDateTime(v); } },
        { data: 'autorizaoutputdate',  render: function (v) { return fmtDateTime(v); } },
        { data: 'staytime',
          render: function (v, type, row) {
              var days = stayDays(row.entrydate);
              var cls  = stayBadgeClass(days);
              var txt  = v || (days + ' días');
              return '<span class="stay-badge ' + cls + '">' + txt + '</span>';
          }
        },
    ],
    'dom': '<"d-none"B>rt<"d-flex justify-content-between align-items-center mt-3"<"text-muted small"i><"pagination-wrap"p>>',
    'lengthMenu': [[25, 50, 100, 200, -1], [25, 50, 100, 200, 'Todos']],
    'pageLength': 25,
    'buttons': [
        {
            'extend'   : 'excel',
            'className': 'buttons-excel d-none',
            'text'     : 'Excel',
            'title'    : 'Monitor de cajas en patio',
        }
    ],
    'language': {
        'emptyTable'     : 'No hay información',
        'info'           : 'Mostrando _START_ a _END_ de _TOTAL_ unidades',
        'infoEmpty'      : 'Sin unidades',
        'infoFiltered'   : '(filtrado de _MAX_ total)',
        'loadingRecords' : 'Cargando…',
        'processing'     : '<i class="fa fa-spinner fa-spin fa-2x"></i>',
        'zeroRecords'    : 'Sin resultados encontrados',
        'paginate': {
            'first'    : '«',
            'last'     : '»',
            'next'     : '›',
            'previous' : '‹',
        }
    },
});
</script>
