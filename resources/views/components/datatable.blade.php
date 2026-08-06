@props([
    'tableId',
    'ajaxUrl',
    'columns',
    'columnDefs' => [],
    'filters' => [],
    'formId' => 'search',
    'scrollY' => '550px',
    'buttons' => ['colvis', 'copy', 'csv', 'excel'],
])

@php
    $jsVarName = str_replace('-', '_', $tableId);
@endphp

<script>
    let oTable_{{ $jsVarName }} = $("#{{ $tableId }}").DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "sScrollY": "{{ $scrollY }}",
        "sScrollX": true,
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "colReorder": true,
        "stateSave": true,
        "stateDuration": 0,
        "ajax": {
            url: '{{ $ajaxUrl }}',
            data: function (d) {
                @foreach($filters as $filter)
                    @if($filter['type'] === 'input')
                        d.{{ $filter['name'] }} = $('input[name={{ $filter['name'] }}]').val();
                    @elseif($filter['type'] === 'select')
                        d.{{ $filter['name'] }} = $('select[name={{ $filter['name'] }}]').val();
                    @endif
                @endforeach
            }
        },
        "columns": {!! json_encode($columns) !!},
        "columnDefs": {!! json_encode($columnDefs) !!},
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[5, 10, 30, 50, 100, 150, 200, 250, -1], [5, 10, 30, 50, 100, 150, 200, 250, "Todos"]],
        buttons: [
            @if(in_array('colvis', $buttons))

            {
                "extend": "colvis",
                "text": "<img src=\"{{asset('/ico/searchmagnifierinterfacesymbol1_79893.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span>Filtrar</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
                columns: ':not(:first):not(:last)'
            },
            @endif
            @if(in_array('copy', $buttons))
            {
                "extend": "copy",
                "text": "<img src=\"{{asset('/ico/copy_paste_document_file_1557.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span>Copiar</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
            },
            @endif
            @if(in_array('csv', $buttons))
            {
                "extend": "csv",
                "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span>Csv</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
            },
            @endif
            @if(in_array('excel', $buttons))
            {
                "extend": "excel",
                "text": "<img src=\"{{asset('/ico/adexportar.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span>Excel</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
            }
            @endif

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
            "processing": '<i class="fa fa-spinner fa-spin fa-4x fa-fw "></i><span class="sr-only">Cargando...</span> ',
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


    $('#{{ $formId }}').on('submit', function (e) {
        oTable_{{ $jsVarName }}.draw();
        e.preventDefault();
    });

</script>
