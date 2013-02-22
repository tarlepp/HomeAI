jQuery(document).ready(function() {
    // Common AJAX setup
    jQuery.ajaxSetup({
        data: {
            module: pageModule,
            action: pageAction
        },
        error: function(jqXHR, exception) {
            var message = '';

            if (jqXHR.status === 0) {
                message = 'Not connect. Verify Network.';
            } else if (jqXHR.status == 404) {
                message = 'Requested page not found [404].';
            } else if (jqXHR.status == 500) {
                message = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                message = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                message = 'Time out error.';
            } else if (exception === 'abort') {
                message = 'Ajax request aborted.';
            } else {
                message = 'Uncaught Error.\n' + jqXHR.responseText;
            }

            makeMessage(message, 'error', {timeout: 5000});
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

    jQuery(document).on('mouseover', '.tooltipTitle', function() {
        createQtipTitle(jQuery(this));
    });
});

function createQtipDiv(el) {
    createQtip(el, el.find('.tooltipDivContainer h1').html(), el.find('.tooltipDivContainer div'), 'auto', 'top left', 'bottom center', true, 100);
}

function createQtipTitle(el) {
    createQtip(el, '', el.attr('title'), 'auto', 'top left', 'bottom center', false, 50);
}

function createQtip(element, tipTitle, tipText, tipWidth, tipMy, tipAt, tipFixed, tipDelay) {
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
            fixed: tipFixed,
            delay: tipDelay,
            effect: false
        },
        position: {
            my: tipMy,
            at: tipAt,
            viewport: jQuery(window)
        }
    });
}

function makeMessage(text, type, options) {
    noty(jQuery.extend({}, {
        text: text,
        type: type
    }, options));
}

function showUrlInDialog(url, urlParameters, options) {
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

function isAppleDevice() {
    return (
        (navigator.userAgent.toLowerCase().indexOf("ipad") > -1) ||
        (navigator.userAgent.toLowerCase().indexOf("iphone") > -1) ||
        (navigator.userAgent.toLowerCase().indexOf("ipod") > -1)
    );
}
