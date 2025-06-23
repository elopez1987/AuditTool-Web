<script>
    $(function () {

        let start_date = moment().subtract(1, 'M');
        let end_date = moment();
        $('#daterange span').html(start_date.format('YYYY-MM-DD') + ' - ' + end_date.format('YYYY-MM-DD'));
        $('#daterange').daterangepicker({
            startDate : start_date,
            endDate : end_date
        }, function(start_date, end_date){
            $('#daterange span').html(start_date.format('YYYY-MM-DD') + ' - ' + end_date.format('YYYY-MM-DD'));
            oTable.draw();
        });

        let oTable = $("#table-evidence").DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "sScrollY": "350px",
            "sScrollX": true,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "colReorder": true,
            "stateSave": true,
            "stateDuration": 0,
            "ajax": {
                url: 'getEvidencesProcess',
                data: function (d) {
                    d.statusid = $('select[name=statusid]').val();
                    d.shipmentid = $('input[name=shipmentid]').val();
                    d.customerid = $('select[name=customerid]').val();
                    d.turnid = $('select[name=turnid]').val();
                    d.categoryid = $('select[name=categoryid]').val();
                    d.from_date = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD');
                    d.to_date = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD');
                }
            },
            "columns": [
                {data: 'center'},
                {data: 'warehouse'},
                {data: 'shipmentid'},
                {data: 'turn'},
                {data: 'origen'},
                {data: 'workday'},
                {data: 'appointment'},
                {data: 'customer'},
                {data: 'provider'},
                {data: 'carrier'},
                {data: 'boxnumber'},
                {data: 'econumber'},
                {data: 'location'},
                {data: 'razon'},
                {data: 'user'},
                {data: 'status'},
                {data: 'btn'},
            ],
            "columnDefs": [
                {"width": "150px", "targets": 0},
                {"width": "120px", "targets": 1},
                {"width": "120px", "targets": 2},
                {"width": "120px", "targets": 3},
                {"width": "150px", "targets": 4},
                {"width": "120px", "targets": 5},
                {"width": "220px", "targets": 6},
                {"width": "220px", "targets": 7},
                {"width": "220px", "targets": 8},
                {"width": "220px", "targets": 9},
                {"width": "220px", "targets": 10},
                {"width": "130px", "targets": 11},
                {"width": "150px", "targets": 12},
                {"width": "180px", "targets": 13},
                {"width": "120px", "targets": 14},
                {"width": "180px", "targets": 15},
                {"width": "180px", "targets": 16},
            ],
            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            "lengthMenu": [[1, 5, 10, 30, 50, 100, 150, 200, 250, -1], [1, 5, 10, 30, 50, 100, 150, 200, 250, "Todos"]],

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
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": '<i class="fa fa-spinner fa-spin fa-4x fa-fw text-info"></i><span class="sr-only">Loading...</span> ',
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

    });
</script>

