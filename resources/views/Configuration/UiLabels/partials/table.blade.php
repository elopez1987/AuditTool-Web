<script>
    let oTable = initDataTable("#table-label-dictionary", {
        serverSide: true,
        ajax: {
            url: '{{ route('label-dictionary.index') }}',
            data: function (d) {
                // filtros adicionales (si los tienes)
            }
        },
        columns: [
            { data: 'module_name',},
            { data: 'field_name',},
            { data: 'label_text' },
            { data: 'type' },
            { data: 'visibled' },
            { data: 'required',},
            { data: 'btn', orderable: false, searchable: false },
        ],
        columnDefs: [
            { width: "380px", targets: 0 },
            { width: "380px", targets: 1 },
            { width: "380px", targets: 2 },
            { width: "180px", targets: 3 },
            { width: "180px", targets: 4 },
            { width: "180px", targets: 5 },
            { width: "180px", targets: 6 },
        ],
    });
    $('#search').on('submit', function (e) {
        oTable.draw();
        e.preventDefault();
    });
</script>