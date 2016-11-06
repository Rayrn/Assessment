/* Custom javascript extending/overwrite bootstrap/jquery defaults */

$(function() {
    $("#add-row").bind("click", function() {
        $.ajax({
            url: "?action=ajax&form=full",
            success: function(result) {
                $('#spacer-row-full').before(result);
            }
        });

        $.ajax({
            url: "?action=ajax&form=mobile",
            success: function(result) {
                $('#spacer-row-mobile').before(result);
            }
        });
    });
});