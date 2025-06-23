<script>
    $('#warehouseid').select2({
        ajax: {
            url: '{{url('getWarehousesForUser')}}',
            success: function (response) {
                $.each(response, function (indice, row) {
                    $("#warehouseid").append("<option value='" + row.warehouseid + "'>" + row.name + "</option>");
                });
                $('#warehouseid').select2();
            }
        }
    });
</script>
