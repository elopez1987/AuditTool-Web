<script>
    $("#centerid").change(event => {
        $.get(`../getUnitStatusForCenter/${event.target.value}`, function (res) {
            $("#unitstatusid").empty();
            res.forEach(element => {
                $("#unitstatusid").append(`<option value=${element.id}> ${element.name} </option>`);
            });
        });
    });
</script>
