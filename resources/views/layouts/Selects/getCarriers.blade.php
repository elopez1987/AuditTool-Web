<script>
    $('#carrierid').select2({
        ajax: {
            url: '{{url('getCarriersSelect')}}',
            success: function (response) {
                $.each(response, function (indice, row) {
                    $("#carrierid").append("<option value='" + row.id + "'>" + row.name + "</option>");
                });
                $('#carrierid').select2();
            }
        }
    });
</script>
