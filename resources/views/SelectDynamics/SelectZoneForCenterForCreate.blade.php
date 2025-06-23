<script>
    $("#centerid").change(event => {
        $.get(`../getZoneForCenter/${event.target.value}`, function (res) {
            $("#zoneid").empty();
            res.forEach(element => {
                $("#zoneid").append(`<option value=${element.id}> ${element.name} </option>`);
            });
        });
    });
</script>
