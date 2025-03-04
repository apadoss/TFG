$(document).ready(function() {
    $("#coreRange").slider({
        range: true,
        min: 0,
        max: 100,
        values: [0, 100],
        slide: function(event, ui) {
            $("#coreRangeMin").text(ui.values[0]);
            $("#coreRangeMax").text(ui.values[1]);
        }
    });

    // Display initial values
    $("#coreRangeMin").text($("#coreRange").slider("values", 0));
    $("#coreRangeMax").text($("#coreRange").slider("values", 1));
});