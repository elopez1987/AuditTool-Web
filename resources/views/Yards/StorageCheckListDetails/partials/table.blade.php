<script>
    var oTable = $("#table-checklist").DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "sScrollY": "250px",
        "serverSide": true,
        "ajax": {
            url: 'getDataCheckList',
            data: function (d) {
                d.name = $('input[name=name]').val();
            }
        },
        "columns": [
            {data: 'center.name', name: 'center.name'},
            {data: 'name'},
            {data: 'auxiliary01'},
            {data: 'auxiliary02'},
            {data: 'auxiliary03'},
            {data: 'auxiliary04'},
            {data: 'auxiliary05'},
            {data: 'status.name', name: 'status.name'},
            {data: 'btn'},
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],

        buttons: [
            {
                "extend": "csv",
                "text": "<i class='fa fa-database'></i> <span  > Exportar Csv</span>",
                "className": "btn waves-effect waves-light btn-outline-secondary btn-sm"
            },
            {
                "extend": "excel",
                "text": "<i class='fa fa-file'></i> <span > Exportar Excel</span>",
                "className": "btn waves-effect waves-light btn-outline-secondary btn-sm"
            }
        ],

        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
    });
    $('#search').on('submit', function (e) {
        oTable.draw();
        e.preventDefault();
    });
</script>

