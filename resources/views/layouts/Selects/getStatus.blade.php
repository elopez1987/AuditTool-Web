<script>
    $('#statusid').select2({
        ajax: {
            url: '{{url('getStatusForSchemaRUS')}}',
            success: function (response) {
                $.each(response, function (indice, row) {
                    $("#statusid").append("<option value='" + row.statusid + "'>" + row.name + "</option>");
                });
                $('#statusid').select2();
            }
        }
    });
</script>
