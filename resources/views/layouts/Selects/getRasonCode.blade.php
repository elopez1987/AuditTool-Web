<script>
    $('#razonstatusid').select2({
        ajax: {
            url: '{{url('getRasonCode')}}',
            success: function (response) {
                $.each(response, function (indice, row) {
                    $("#razonstatusid").append("<option value='" + row.id + "'>" + row.name + "</option>");
                });
                $('#razonstatusid').select2();
            }
        }
    });
</script>
