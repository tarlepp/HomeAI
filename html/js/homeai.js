
jQuery(document).ready(function() {
    $(document).on('click', function(e){
        if ($(".ui-dialog").length) {
            if (!$(e.target).parents().filter('.ui-dialog').length) {
                $('.ui-dialog-content').dialog('close');
            }
        }
    });
});