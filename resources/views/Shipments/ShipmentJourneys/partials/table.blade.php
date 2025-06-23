<script>
    var oTable = $("#table-journeys").DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "sScrollY": "500px",
        "serverSide": true,
        "sScrollX": true,
        "responsive": true,
        "colReorder": true,
        "stateSave": true,
        "stateDuration": 0,
        "processing": true,
        "ajax": {
            url: 'getDataJourney',
            data: function (d) {
                d.shipmentid = $('input[name=shipmentid]').val();
                d.customername = $('input[name=customername]').val();
                d.carrierid = $('select[name=carrierid]').val();
            }
        },
        "columns": [
            {data: 'shipmentid'},
            {data: 'carriername'},
            {data: 'boxnumber'},
            {data: 'customername'},
            {data: 'city'},
            {data: 'downloaddate'},
            {data: 'loadingdate'},
            {data: 'entrydateunit'},
            {data: 'goodissuedate'},
            {data: 'autorizaoutputdate'},
            {data: 'outputdateunitreal'},
            {data: 'outputdatestimate'},
            {data: 'statusunit'},
            {data: 'journeystatus'},
            {data: 'location'},
            {data: 'eta'},
            {data: 'arrivedate'},
            {data: 'auxiliary02'},
            {data: 'receiptenddate'},
            {data: 'razonoutputname'},
            {data: 'auxiliary04'},
            {data: 'auxiliary03'},
            {data: 'btn'},
        ],
        "columnDefs": [
            {"width": "80px", "targets": 0},
            {"width": "300px", "targets": 1},
            {"width": "130px", "targets": 2},
            {"width": "250px", "targets": 3},
            {"width": "230px", "targets": 4},
            {"width": "150px", "targets": 5},
            {"width": "130px", "targets": 6},
            {"width": "150px", "targets": 7},
            {"width": "150px", "targets": 8},
            {"width": "150px", "targets": 9},
            {"width": "150px", "targets": 10},
            {"width": "150px", "targets": 11},
            {"width": "150px", "targets": 12},
            {"width": "180px", "targets": 13},
            {"width": "180px", "targets": 14},
            {"width": "180px", "targets": 15},
            {"width": "180px", "targets": 16},
            {"width": "180px", "targets": 17},
            {"width": "180px", "targets": 18},
            {"width": "180px", "targets": 19},
            {"width": "280px", "targets": 20},
            {"width": "280px", "targets": 21},
            {"width": "180px", "targets": 22},
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[5,10,15,25,30,40,50, 100,-1], [5,10,15,25,30,40, 50,100, "Todos"]],
        buttons: [
            {
                "extend": "colvis",
                "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt=''><span  style='font-size: 11px;'> Mostar/Ocultar</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
                columns: ':not(:first):not(:last)'
            },
            {
                "extend": "copy",
                "text": "<img src=\"{{asset('/pics/adcopiar.png')}}\"  alt=''><span  style='font-size: 11px;'> Copiar</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
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
            "processing": '<i class="fa fa-spinner fa-spin fa-4x fa-fw text-info"></i><span class="sr-only">Cargando...</span> ',
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

