<script>
    $("#centerid").change(event => {
        $.get(`../getRasonsForCenter/${event.target.value}`, function (res) {
            $("#razonstatusid").empty();
            res.forEach(element => {
                $("#razonstatusid").append(`<option value=${element.id}> ${element.name} </option>`);
            });
        });
    });
</script>
