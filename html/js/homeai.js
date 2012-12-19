jQuery(document).ready(function() {
    // Common AJAX setup
    jQuery.ajaxSetup({
        data: {
            module: pageModule,
            action: pageAction
        },
        error: function(jqXHR, exception) {
            if (jqXHR.status === 0) {
                makeMessage('Not connect. Verify Network.', 'error');
            } else if (jqXHR.status == 404) {
                makeMessage('Requested page not found [404].', 'error');
            } else if (jqXHR.status == 500) {
                makeMessage('Internal Server Error [500].', 'error');
            } else if (exception === 'parsererror') {
                makeMessage('Requested JSON parse failed.', 'error');
            } else if (exception === 'timeout') {
                makeMessage('Time out error.', 'error');
            } else if (exception === 'abort') {
                makeMessage('Ajax request aborted.', 'error');
            } else {
                makeMessage('Uncaught Error.\n' + jqXHR.responseText, 'error');
            }
        },
        dataType: "json",
        type: "post"
    });

    // Global jQuery UI Dialog close functionality
    jQuery(document).on('click', function(e) {
        if (jQuery(".ui-dialog").length) {
            if (!jQuery(e.target).parents().filter('.ui-dialog').length) {
                jQuery('.ui-dialog-content').dialog('close');
            }
        }
    });

    // Global tooltip event
    jQuery(document).on('mouseover', '.tooltipDiv', function() {
        createQtipDiv(jQuery(this));
    });
});

function createQtipDiv(el) {
    createQtip(el, el.find('.tooltipDivContainer h1').html(), el.find('.tooltipDivContainer div'), 'auto', 'top left', 'bottom left');
}

function createQtip(element, tipTitle, tipText, tipWidth, tipMy, tipAt) {
    element.qtip({
        metadata: {
            type: 'html5',      // Use HTML5 data-* attributes
            name: 'qtipopts'    // Grab the metadata from the data-qtipOpts HTML5 attribute
        },
        content: {
            title: tipTitle,
            text: tipText
        },
        style: {
            tip: {
                corner: true
            },
            classes: 'ui-tooltip-rounded',
            widget: true,
            width: tipWidth
        },
        show: {
            ready: true
        },
        hide: {
            fixed: true,
            delay: 100,
            effect: false
        },
        position: {
            my: tipMy,
            at: tipAt,
            viewport: jQuery(window)
        }
    });
}

function makeMessage(text, type) {
    console.log('asdfasdf');
    noty({
        text: text,
        type: type
    });
}

function showUrlInDialog(url, urlParameters, options){
    options = options || {};

    var tag = jQuery("<div></div>"); //This tag will the hold the dialog content.

    jQuery.ajax({
        url: url,
        type: (options.type || 'GET'),
        dataType: (options.dataType || 'text'),
        data: urlParameters,
        beforeSend: options.beforeSend,
        error: options.error,
        complete: options.complete,
        success: function(data, textStatus, jqXHR) {
            if (typeof data == "object" && data.html) {
                //response is assumed to be JSON
                tag.html(data.html).dialog(options.dialog).dialog('open');
            } else {
                //response is assumed to be HTML
                tag.html(data).dialog(options.dialog).dialog('open');
            }

            jQuery.isFunction(options.success) && (options.success)(data, textStatus, jqXHR);
        }
    });
}