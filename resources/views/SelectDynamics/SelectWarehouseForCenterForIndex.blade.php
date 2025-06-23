<script>
    $("#centerid").change(event => {
        $.get(`/getWarehouseForCenter/${event.target.value}`, function (res) {
            $("#warehouseid").empty();
            res.forEach(element => {
                $("#warehouseid").append(`<option value=${element.warehouseid}> ${element.name} </option>`);
            });
        });
    });
</script>
