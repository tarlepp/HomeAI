/*
 * dashboard 1.0
 * http://connect.gxsoftware.com/dashboardplugin/demo/dashboard.html
 *
 * Copyright (c) 2010 Mark Machielsen
 *
 * Dual licensed under the MIT and GPL licenses (same as jQuery):
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 */

(function (jQuery) { // Create closure.

    // Constructor for dashboard object.
    jQuery.fn.dashboard = function(options) {
        // Public properties of dashboard.
        var dashboard = {};

        dashboard.layout;
        dashboard.element = this;
        dashboard.id = this.attr("id");
        dashboard.widgets = {};
        dashboard.widgetsToAdd = {};
        dashboard.widgetCategories = {};
        dashboard.initialized = false;

        // Public methods
        dashboard.serialize = function() {
            dashboard.log('entering serialize function', 1);

            var widgetData = [];
            var columns = jQuery('.' + opts.columnClass);

            if (columns.length == 0) {
                dashboard.log(opts.columnClass + ' class not found', 5);
            }

            columns.each(function () {
                jQuery(this).children().each(function () {
                    if (jQuery(this).hasClass(opts.widgetClass)) {
                        widgetData.push((dashboard.getWidget(jQuery(this).attr("id"))).serialize());
                    }
                });
            });

            return {
                layout: dashboard.layout.id,
                data:   widgetData
            };
        };

        dashboard.log = function(msg, level) {
            if (level >= opts.debuglevel && typeof console != 'undefined') {
                var l = '';
                if (level == 1) l = 'INFO';
                if (level == 2) l = 'EVENT';
                if (level == 3) l = 'WARNING';
                if (level == 5) l = 'ERROR';
                console.log(l + ' - ' + msg);
            }
        };

        dashboard.setLayout = function(layout) {
            if (layout != null) {
                dashboard.log('entering setLayout function with layout ' + layout.id, 1);
            } else {
                dashboard.log('entering setLayout function with layout null', 1);
            }

            dashboard.layout = layout;
            loading.remove();

            if (dashboard.layout != null) {
                if (typeof opts.layoutClass != 'undefined') {
                    this.element.find('.' + opts.layoutClass).addClass(dashboard.layout.classname);
                } else {
                    this.element.html(dashboard.layout.html);
                }
            }

            var columns = jQuery('.' + opts.columnClass);

            // make the columns sortable, see http://jqueryui.com/demos/sortable/ for explanation
            columns.sortable({
                connectWith: columns,
                handle: '.' + opts.widgetHeaderClass,
                over: function() {
                    jQuery(this).addClass("selectedcolumn");
                },
                out: function() {
                    jQuery(this).removeClass("selectedcolumn");
                },
                receive: function(event, ui) {
                    // update the column attribute for the widget
                    var w = dashboard.getWidget(ui.item.attr("id"));
                    w.column = getColumnIdentifier(jQuery(this).attr("class"));

                    dashboard.log('dashboardStateChange event thrown for widget ' + w.id, 2);
                    dashboard.element.trigger("dashboardStateChange", {"stateChange":"widgetMoved", "widget":w});

                    dashboard.log('widgetDropped event thrown for widget ' + w.id, 2);
                    w.element.trigger("widgetDropped", {"widget": w});
                },
                deactivate: function() {
                    // This event is called for each column
                    dashboard.log('Widget is dropped: check if the column is now empty.', 1);

                    var childLength = jQuery(this).children().length;

                    if (childLength == 0) {
                        dashboard.log('adding the empty text to the column', 1);

                        jQuery(this).html('<div class="emptycolumn">' + opts.emptyColumnHtml + '</div>');
                    } else {
                        if (childLength == 2) {
                            // remove the empty column HTML
                            jQuery(this).find('.emptycolumn').remove();
                        }
                    }
                },
                start: function(event, ui) {
                    columns.css('height', columns.height() + (jQuery(window).height() - columns.height()));

                    ui.item.find('.' + opts.widgetTitleClass).addClass('noclick');
                    ui.item.addClass('draggedWidget');

                    columns.find('.widget').each(function() {
                        jQuery(this).addClass('draggActivated')
                    });

                    jQuery('footer').hide();
                },
                stop: function(event, ui) {
                    columns.css('height', columns.height() - (jQuery(window).height() - columns.height()));

                    //sorting changed (within one list)
                    setTimeout(function() {
                        ui.item.find('.' + opts.widgetTitleClass).removeClass('noclick');
                    }, 300);

                    ui.item.removeClass('draggedWidget');

                    columns.find('.widget').each(function() {
                        jQuery(this).removeClass('draggActivated')
                    });

                    columns.each(function() {
                        var height = 0;

                        jQuery(this).find('.widget').each(function() {
                            height += jQuery(this).height() + 10;
                        });

                        jQuery(this).height(height + 10);
                    });

                    jQuery('footer').show();
                }
            });

            fixSortableColumns();

            // trigger the dashboardLayoutLoaded event
            dashboard.log('dashboardLayoutLoaded event thrown', 2);
            dashboard.element.trigger("dashboardLayoutLoaded");
        };

        // This is a workaround for the following problem: when I drag a widget from column2 to column1, sometimes the widget is
        // moved to column3, which is not visible
        function fixSortableColumns() {
            dashboard.log('entering fixSortableColumns function', 1);

            jQuery('.nonsortablecolumn').removeClass('nonsortablecolumn').addClass(opts.columnClass);

            jQuery('.' + opts.columnClass).filter(function() {
                return jQuery(this).css("display") == 'none'
            }).addClass('nonsortablecolumn').removeClass(opts.columnClass);
        }

        function getColumnIdentifier(classes) {
            dashboard.log('entering getColumnIdentifier function', 1);

            var r;
            var s = classes.split(" ");

            for (var i = 0; i < s.length; i++) {
                if (s[i].indexOf(opts.columnPrefix) === 0) {
                    r = s[i]
                }
            }

            return r.replace(opts.columnPrefix, '');
        }

        dashboard.loadLayout = function() {
            dashboard.log('entering loadLayout function', 1);

            if (typeof opts.json_data.url != 'undefined' && opts.json_data.url.length > 0) {
                dashboard.log('Getting JSON feed : ' + opts.json_data.url, 1);

                jQuery.getJSON(opts.json_data.url, function(json) {
                    if (json == null) {
                        alert('Unable to get json. If you are using chrome: there is an issue with loading json with local files. It works on a server :-)', 5);
                        return;
                    }

                    // set the layout
                    var obj = json.result;
                    var currentLayout = (typeof dashboard.layout != 'undefined') ? dashboard.layout : getLayout(obj.layout);

                    dashboard.setLayout(currentLayout);
                    dashboard.loadWidgets(obj.data);
                });
            } else {
                alert("No json_data.url defined!");
            }
        };

        dashboard.addWidget = function(obj, column) {
            dashboard.log('entering addWidget function', 1);

            // add the widget to the column
            var wid = obj.id;
            var wi = jQuery('#' + wid);
            var template = jQuery('#' + opts.widgetTemplate);

            // check if the widget is already registered and available in the dom
            if (typeof dashboard.widgets[wid] != 'undefined' && wi.length > 0) {
                column = dashboard.widgets[wid].column;

                // add it to the column
                wi.appendTo(column);
            } else {
                // build the widget
                dashboard.log('Applying template : ' + opts.widgetTemplate, 1);
                if (template.length == 0) {
                    dashboard.log('Template "' + opts.widgetTemplate + ' not found', 5);
                }

                var widgetStr = jQuery.trim(tmpl(template.html(), obj));

                wi = jQuery(widgetStr);

                // add it to the column
                wi.appendTo(column);

                dashboard.widgets[wid] = widget({
                    id:         wid,
                    element:    wi,
                    column:     obj.column,
                    url:        (typeof obj.url != 'undefined' ? obj.url : null),
                    editurl:    obj.editurl,
                    title:      obj.title,
                    open:       obj.open,
                    metadata:   obj.metadata,
                    refresh:    (typeof obj.refresh != 'undefined' ? obj.refresh : 0),
                    method:     obj.method
                });
            }

            dashboard.log('widgetAdded event thrown for widget ' + wid, 2);
            dashboard.widgets[wid].element.trigger("widgetAdded", {"widget":dashboard.widgets[wid]});

            if (dashboard.initialized) {
                dashboard.log('dashboardStateChange event thrown for widget ' + wid, 2);
                dashboard.element.trigger("dashboardStateChange", {"stateChange":"widgetAdded", "widget":wi});
            }
        };

        dashboard.loadWidgets = function(data) {
            dashboard.log('entering loadWidgets function', 1);
            dashboard.element.find('.' + opts.columnClass).empty();

            // This is for the manual feed
            jQuery(data).each(function() {
                var column = this.column;

                dashboard.addWidget(this, dashboard.element.find('.' + opts.columnPrefix + column));
            });

            // check if there are widgets in the temp dashboard which needs to be moved
            // this is not the correct place, but otherwise we are too late
            var tempDashboard = jQuery('#tempdashboard');

            // check if there are still widgets in the temp
            tempDashboard.find('.' + opts.widgetClass).each(function() {
                // append it to the first column
                var firstCol = dashboard.element.find('.' + opts.columnClass + ':first');

                jQuery(this).appendTo(firstCol);

                // set the new column
                dashboard.getWidget(jQuery(this).attr("id")).column = firstCol.attr("id");
            });

            tempDashboard.remove();

            var columns = jQuery('.' + opts.columnClass);
            var heights = [];

            // add the text to the empty columns
            columns.each(function(index) {
                if (jQuery(this).children().length == 0) {
                    jQuery(this).html('<div class="emptycolumn">' + opts.emptyColumnHtml + '</div>');
                }

                heights[index] = jQuery(this).outerHeight();
            });

            columns.each(function() {
                jQuery(this).css('minHeight', Math.max.apply(Math, heights));
            });

            dashboard.initialized = true;
        };

        dashboard.init = function() {
            dashboard.log('entering init function', 1);

            // load the widgets as fast as we can. After that add the binding
            dashboard.loadLayout();
        };

        dashboard.getWidget = function(id) {
            dashboard.log('entering getWidget function', 1);

            var wi = dashboard.widgets[id];

            if (typeof wi != 'undefined') {
                return wi;
            } else {
                return null;
            }
        };

        // Merge in the caller's options with the defaults.
        var opts = jQuery.extend({}, jQuery.fn.dashboard.defaults, options);
        var addOpts = jQuery.extend({}, jQuery.fn.dashboard.defaults.addWidgetSettings, options.addWidgetSettings);
        var layoutOpts = jQuery.extend({}, jQuery.fn.dashboard.defaults.editLayoutSettings, options.editLayoutSettings);
        var refreshOpts = jQuery.extend({}, jQuery.fn.dashboard.defaults.refreshSettings, options.refreshSettings);

        // Execution 'forks' here and restarts in init().  Tell the user we're busy with a loading.
        var loading = jQuery(opts.loadingHtml).appendTo(dashboard.element);

        /**
         * widget object
         *    Private sub-class of dashboard
         * Constructor starts
         */
        function widget(widget) {
            dashboard.log('entering widget constructor', 1);

            // Merge default options with the options defined for this widget.
            widget = jQuery.extend({}, jQuery.fn.dashboard.widget.defaults, widget);

            // public functions
            widget.openContent = function() {
                // hide the open link, show the close link
                widget.element.find('.widgetOpen').hide();
                widget.element.find('.widgetClose').show();

                dashboard.log('entering openContent function', 1);

                widget.open = true;

                if (!widget.loaded) {
                    // load the content in the widget if the state == open
                    if (this.url != '' && this.url != null && typeof this.url != 'undefined') {
                        // add the loading
                        jQuery(opts.loadingHtml).appendTo(widget.element.find('.' + opts.widgetContentClass));

                        dashboard.log('widgetShow event thrown for widget ' + widget.id, 2);

                        widget.element.trigger("widgetShow", {"widget": widget});

                        widget.element.find('.' + opts.widgetContentClass).load(this.url, function(response, status) {
                            if (status == "error") {
                                widget.element.find('.' + opts.widgetContentClass).html(opts.widgetNotFoundHtml);
                            }

                            dashboard.log('widgetLoaded event thrown for widget ' + widget.id, 2);

                            widget.loaded = true;
                            widget.element.trigger("widgetLoaded", {"widget": widget});
                        });
                    } else {
                        dashboard.log('widgetShow event thrown for widget ' + widget.id, 2);
                        widget.element.trigger("widgetShow", {"widget": widget});

                        dashboard.log('widgetLoaded event thrown', 2);
                        widget.element.trigger("widgetLoaded", {"widget": widget});
                    }
                } else {
                    dashboard.log('widgetShow event thrown for widget ' + widget.id, 2);
                    widget.element.trigger("widgetShow", {"widget": widget});
                }

                if (dashboard.initialized) {
                    dashboard.log('dashboardStateChange event thrown for widget ' + widget.id, 2);
                    dashboard.element.trigger("dashboardStateChange", {"stateChange": "widgetOpened", "widget": widget});
                }
            };

            widget.refreshContent = function() {
                dashboard.log('entering refreshContent function', 1);

                widget.loaded = false;

                if (widget.open) {
                    widget.openContent();
                }

                widget.setTitle(widget.title);
            };

            widget.refreshContentSilently = function() {
                dashboard.log('entering refreshContentSilently function', 1);

                if (widget.open) {
                    var parameters = jQuery.extend(
                        {},
                        widget.metadata.data,
                        {
                            widgetData: {
                                id: widget.id
                            }
                        }
                    );

                    switch (widget.metadata.type) {
                        case 'curl':
                            handleRequest('Widget/Curl', widget, parameters);
                            break;
                        case 'rss':
                            handleRequest('Widget/Rss', widget, parameters);
                            break;
                        case 'highcharts':
                            handleRequest('Widget/Highcharts', widget, parameters);
                            break;
                    }
                }
            };

            function handleRequest(url, widget, parameters) {
                var dataType = 'text';

                switch (widget.metadata.type) {
                    case 'curl':
                    case 'rss':
                        dataType = 'json';
                        break;
                    case 'highcharts':
                        break;
                }

                jQuery.ajax({
                    url: pageBaseHref + url,
                    data: parameters,
                    dataType: dataType,
                    success: function(data) {
                        var widgetElement = jQuery('#'+ widget.id).find('.widgetcontent');

                        if (dataType == 'json') {
                            data = data.content;
                        }

                        if (widgetElement.html() != data) {
                            widgetElement.html(data);
                        }
                    }
                });
            }

            widget.setTitle = function(newTitle) {
                dashboard.log('entering setTitle function', 1);

                widget.title = newTitle;
                widget.element.find('.' + opts.widgetTitleClass).html(newTitle);

                if (dashboard.initialized) {
                    dashboard.log('dashboardStateChange event thrown for widget ' + widget.id, 2);
                    dashboard.element.trigger("dashboardStateChange", {"stateChange": "titleChanged", "widget": widget});
                }
            };

            widget.closeContent = function() {
                dashboard.log('entering closeContent function', 1);
                widget.open = false;

                dashboard.log('widgetHide event thrown for widget ' + widget.id, 2);
                widget.element.trigger("widgetHide", {"widget": widget});

                // show the open link, hide the close link
                widget.element.find('.widgetOpen').show();
                widget.element.find('.widgetClose').hide();

                dashboard.log('dashboardStateChange event thrown for widget ' + widget.id, 2);
                dashboard.element.trigger("dashboardStateChange", {"stateChange": "widgetClosed", "widget": widget});
            };

            widget.addMetadataValue = function(name, value) {
                dashboard.log('entering addMetadataValue function', 1);

                if (typeof widget.metadata == 'undefined') {
                    widget.metadata = {};
                }

                widget.metadata[name] = value;

                dashboard.log('dashboardStateChange event thrown for widget ' + widget.id, 2);
                dashboard.element.trigger("dashboardStateChange", {"stateChange":"metadataChanged", "widget": widget});
            };

            widget.openMenu = function() {
                dashboard.log('entering openMenu function', 1);
                widget.element.find('.' + opts.menuClass).show();
            };

            widget.closeMenu = function() {
                dashboard.log('entering closeMenu function', 1);
                widget.element.find('.' + opts.menuClass).hide();
            };

            widget.remove = function() {
                dashboard.log('entering remove function', 1);
                widget.element.remove();

                dashboard.log('widgetDeleted event thrown for widget ' + widget.id, 2);
                widget.element.trigger('widgetDeleted', {"widget": widget});

                dashboard.log('dashboardStateChange event thrown for widget ' + widget.id, 2);
                dashboard.element.trigger("dashboardStateChange", {"stateChange":"widgetRemoved", "widget": widget});
            };

            widget.serialize = function() {
                dashboard.log('entering serialize function', 1);

                var output = {
                    title:      widget.title,
                    id:         widget.id,
                    column:     widget.column,
                    editurl:    widget.editurl,
                    open:       widget.open,
                    url:        widget.url,
                    refresh:    widget.refresh,
                    method:     widget.method
                };

                if (typeof widget.metadata != 'undefined') {
                    output['metadata'] = widget.metadata;
                }

                return output;
            };

            widget.openFullscreen = function() {
                dashboard.log('entering openFullscreen function', 1);

                widget.fullscreen = true;

                // hide the layout div first
                jQuery('.' + opts.columnClass).hide();

                // move the widget that is maximized to a new fullscreen ul
                var fs = jQuery('<ul class="fullscreen" id="fullscreen_' + dashboard.id + '"></ul>');
                fs.appendTo(dashboard.element);

                widget.element.parent().attr("id", "placeholder");
                widget.element.appendTo(fs);
            };

            widget.closeFullscreen = function () {
                dashboard.log('entering closeFullscreen function', 1);

                widget.fullscreen = false;

                // move the widget back to the placeholder
                widget.element.appendTo(jQuery('#placeholder'));

                // remove the fullscreen
                jQuery('#fullscreen_' + dashboard.id).remove();

                // and show the layout div
                jQuery('.' + opts.columnClass).show();
            };

            widget.openSettings = function () {
                dashboard.log('entering openSettings function', 1);

                if (typeof opts.widgetSetupUrl != 'undefined' && opts.widgetSetupUrl != null && opts.widgetSetupUrl != '') {
                    var data = {
                        data: widget.serialize(),
                        widget: {}
                    };

                    widgetSetupDialog.empty();

                    jQuery.ajax({
                        url: opts.widgetSetupUrl + widget.method,
                        type: 'POST',
                        data: data,
                        dataType: 'text',
                        success: function(data) {
                            widgetSetupDialog.html(data);

                            dashboard.element.trigger('addWidgetDialogSetupsLoaded', [widget, true]);
                        }
                    });

                } else {
                    alert('Widget setup url not defined');
                }

                return false;
            };

            // called when widget is initialized
            if (widget.open) {
                widget.openContent();
            }

            widget.initialized = true;

            dashboard.log('widgetInitialized event thrown', 2);

            widget.element.trigger("widgetInitialized", {"widget": widget});

            return widget;
        }


        // FIXME: can this be done easier??
        function getLayout(id) {
            dashboard.log('entering getLayout function', 1);

            var r = null;
            var first = null;

            if (typeof opts.layouts != 'undefined') {
                jQuery.each(opts.layouts, function (i, item) {
                    if (i == 0) {
                        first = item;
                    }

                    if (item.id == id) {
                        r = item;
                    }
                });
            }

            if (r == null) {
                r = first
            }

            return r;
        }

        // TODO
        jQuery(document).on('dashboardLayoutLoaded', '#' + dashboard.id, function() {
            jQuery('#'+ dashboard.id).find('.'+ opts.columnClass).each(function() {
                var column = jQuery(this);

                column.children().each(function() {
                    console.log('todo normalize column heights');
                });
            });
        });

        jQuery(document).on('click', '#' + dashboard.id + ' .menutrigger', function() {
            dashboard.log('widgetOpenMenu event thrown for widget ' + widget.id, 2);
            var wi = dashboard.getWidget(jQuery(this).closest('.' + opts.widgetClass).attr("id"));

            wi.element.trigger('widgetOpenMenu', {"widget":wi});
            return false;
        });

        jQuery(document).on('click', '#' + dashboard.id + ' .' + opts.widgetFullScreenClass, function() {
            // close the menu
            dashboard.log('widgetCloseMenu event thrown for widget ' + widget.id, 2);

            var wi = dashboard.getWidget(jQuery(this).closest('.' + opts.widgetClass).attr("id"));
            wi.element.trigger('widgetCloseMenu', {"widget": wi});

            if (wi.fullscreen) {
                dashboard.log('widgetCloseFullScreen event thrown for widget ' + wi.id, 2);

                wi.element.trigger('widgetCloseFullScreen', {"widget":wi});
            } else {
                dashboard.log('widgetOpenFullScreen event thrown for widget ' + wi.id, 2);

                wi.element.trigger('widgetOpenFullScreen', {"widget":wi});
            }

            return false;
        });

        jQuery(document).on('click', '#' + dashboard.id + ' a.controls', function() {
            var wi = dashboard.getWidget(jQuery(this).closest('.' + opts.widgetClass).attr("id"));

            wi.element.trigger(jQuery(this).data('control'), {"widget": wi});

            return false;
        });

        // add the menu events (by default triggers are connected in dashboard_jsonfeed)
        jQuery(document).on('widgetCloseMenu', '#' + dashboard.id + ' .' + opts.widgetClass, function(e, o) {
            dashboard.log("Event '"+ e.type +"' for widget " + jQuery(this).attr("id"), 1);

            o.widget.closeMenu();
        });

        jQuery(document).on('widgetOpenMenu', '#' + dashboard.id + ' .' + opts.widgetClass, function(e, o) {
            dashboard.log("Event '"+ e.type +"' for widget " + jQuery(this).attr("id"), 1);

            o.widget.openMenu();
        });

        jQuery(document).on('widgetDelete', '#' + dashboard.id + ' .' + opts.widgetClass, function(e, o) {
            dashboard.log("Event '"+ e.type +"' for widget " + jQuery(this).attr("id"), 1);

            var dialog = jQuery("<div></div>");

            dialog.html(opts.deleteConfirmMessage).dialog({
                resizable: false,
                draggable: false,
                height: 115,
                modal: true,
                title: 'Confirm widget removal',
                buttons: {
                    'close': {
                        'text': 'Close',
                        'class': 'btn',
                        'click': function() {
                            jQuery(this).dialog( "close" );

                            if (o.edit) {
                                jQuery('#'+ o.widget.id).trigger('widgetEdit', {"widget": o.widget});
                            }
                        }
                    },
                    'remove': {
                        'text': 'Remove widget',
                        'class': 'btn btn-danger pull-left',
                        'click': function() {
                            dashboard.log("Removing widget " + jQuery(this).attr("id"), 1);
                            o.widget.remove();

                            jQuery(this).dialog( "close" );
                        }
                    }
                }
            }).dialog('open');
        });

        jQuery(document).on('widgetRefresh', '#' + dashboard.id + ' .' + opts.widgetClass, function(e, o) {
            dashboard.log("Event '"+ e.type +"' for widget " + jQuery(this).attr("id"), 1);

            o.widget.refreshContent();
        });

        jQuery(document).on('widgetSetTitle', '#' + dashboard.id + ' .' + opts.widgetClass, function(e, o) {
            dashboard.log("Event '"+ e.type +"' for widget " + jQuery(this).attr("id"), 1);

            o.widget.setTitle(o.title);
        });

        jQuery(document).on('widgetClose', '#' + dashboard.id + ' .' + opts.widgetClass, function(e, o) {
            dashboard.log("Event '"+ e.type +"' for widget " + jQuery(this).attr("id"), 1);

            o.widget.closeContent();
        });

        jQuery(document).on('widgetOpen', '#' + dashboard.id + ' .' + opts.widgetClass, function(e, o) {
            dashboard.log("Event '"+ e.type +"' for widget " + jQuery(this).attr("id"), 1);

            o.widget.openContent();
        });

        jQuery(document).on('widgetShow', '#' + dashboard.id + ' .' + opts.widgetClass, function(e) {
            dashboard.log("Event '"+ e.type +"' for widget " + jQuery(this).attr("id"), 1);

            jQuery(this).find('.' + opts.widgetContentClass).show();
        });

        jQuery(document).on('widgetHide', '#' + dashboard.id + ' .' + opts.widgetClass, function(e) {
            dashboard.log("Event '"+ e.type +"' for widget " + jQuery(this).attr("id"), 1);

            jQuery(this).find('.' + opts.widgetContentClass).hide();
        });

        jQuery(document).on('widgetAddMetadataValue', '#' + dashboard.id + ' .' + opts.widgetClass, function(e, o) {
            dashboard.log("Event '"+ e.type +"' for widget " + jQuery(this).attr("id") +", metadata name: " + o.name + ", value: " + o.value, 1);

            o.widget.addMetadataValue(o.name, o.value);
        });

        jQuery(document).on('widgetOpenFullScreen', '#' + dashboard.id + ' .' + opts.widgetClass, function(e, o) {
            dashboard.log("Event '"+ e.type +"' for widget " + jQuery(this).attr("id"), 1);

            o.widget.openFullscreen();
        });

        jQuery(document).on('widgetCloseFullScreen', '#' + dashboard.id + ' .' + opts.widgetClass, function(e, o) {
            dashboard.log("Event '"+ e.type +"' for widget " + jQuery(this).attr("id"), 1);

            o.widget.closeFullscreen();
        });

        jQuery(document).on('widgetEdit', '#' + dashboard.id + ' .' + opts.widgetClass, function(e, o) {
            dashboard.log("Event '"+ e.type +"' for widget " + jQuery(this).attr("id"), 1);

            o.widget.openSettings();
        });

        // Define a toggle event when clicking at the header
        jQuery(document).on('click', '#' + dashboard.id + ' .' + opts.widgetTitleClass, function() {
            dashboard.log("Click on the header detected for widget " + jQuery(this).attr("id"), 1);

            if (!jQuery(this).hasClass('noclick')) {
                var wi = dashboard.getWidget(jQuery(this).closest('.' + opts.widgetClass).attr("id"));

                if (wi.open) {
                    dashboard.log('widgetClose event thrown for widget ' + wi, 2);

                    wi.element.trigger('widgetClose', {"widget": wi});
                } else {
                    dashboard.log('widgetOpen event thrown for widget ' + wi, 2);

                    wi.element.trigger('widgetOpen', {"widget": wi});
                }
            }

            return false;
        });

        var addWidgetDialog = jQuery('#' + addOpts.dialogId);

        if (addWidgetDialog.length == 0) {
            dashboard.log('Unable to find ' + addOpts.dialogId, 5);
        }

        addWidgetDialog.dialog({
            autoOpen: false,
            height: 500,
            width: 700,
            modal: true,
            resizable: false,
            draggable: false,
            buttons: {
                'close': {
                    text: 'Close',
                    class: 'btn',
                    'click': function() {
                        jQuery(this).dialog( "close" );
                    }
                }
            }
        });

        var layoutDialog = jQuery('#' + layoutOpts.dialogId);

        if (layoutDialog.length == 0) {
            dashboard.log('Unable to find ' + layoutOpts.dialogId, 5);
        }

        layoutDialog.dialog({
            autoOpen: false,
            height: 180,
            width: 500,
            modal: true,
            resizable: false,
            draggable: false,
            buttons: {
                'close': {
                    text: 'Close',
                    class: 'btn',
                    click: function() {
                        jQuery(this).dialog( "close" );
                    }
                }
            }
        });

        var widgetSetupDialog = jQuery('#widgetSetupDialog');

        widgetSetupDialog.dialog({
            autoOpen: false,
            height: 400,
            width: 600,
            modal: true,
            resizable: false,
            draggable: false,
            open: function(event, ui) {
                dashboard.log('Widget setup event ' + event +' thrown.', 1);

                var form = jQuery(this).find('#widgetSetupForm');

                if (typeof getWidgetValidationRules == 'function') {
                    var options = {
                        ignore: '',
                        errorClass: 'error',
                        validClass: '',
                        errorElement: 'span',
                        invalidHandler: function(form, validator) {
                            var errors = validator.numberOfInvalids();

                            if (errors) {
                                var message = errors == 1
                                    ? 'Error in widget configuration! You missed 1 field. This field has been highlighted.'
                                    : 'Errors in widget configuration! You missed ' + errors + ' fields. These fields have been highlighted.';

                                makeMessage(message, 'error', {timeout: 7000});

                                jQuery(".tab-content").find("div.tab-pane:hidden:has(div.error)").each(function() {
                                    var id = jQuery(this).attr("id");

                                    jQuery('#widgetSetupNavigation').find('a[href="#'+ id +'"]').tab('show');
                                });
                            }
                        },
                        highlight: function(element, errorClass, validClass) {
                            if (element.type === 'radio') {
                                this.findByName(element.name).closest('div.control-group').removeClass(validClass).addClass(errorClass);
                            } else {
                                jQuery(element).closest('div.control-group').removeClass(validClass).addClass(errorClass);
                            }
                        },
                        unhighlight: function(element, errorClass, validClass) {
                            if (element.type === 'radio') {
                                this.findByName(element.name).parent('div').parent('div').removeClass(errorClass).addClass(validClass);
                            } else {
                                jQuery(element).closest('div.control-group').removeClass(errorClass).addClass(validClass);
                                jQuery(element).next('span.help-block').text('');
                            }
                        },
                        errorPlacement: function(error, element) {
                            var isInputAppend = (jQuery(element).parent('div.input-append').length > 0);

                            error.addClass('help-block');

                            if (isInputAppend) {
                                var appendElement = jQuery(element).parent();

                                error.addClass('span9');
                                error.insertAfter(appendElement);
                            }else {
                                error.insertAfter(element);
                            }
                        }
                    };

                    form.validate(jQuery.extend(options, getWidgetValidationRules()));
                }

                var notFound = jQuery(this).parent().find('#widgetSetupNotFound');
                var buttonContainer = jQuery(this).parent().parent().find('div.ui-dialog-buttonset');

                buttonContainer.find('button').each(function() {
                    var button = jQuery(this);

                    if (notFound.length > 0 && !button.hasClass('nohide')) {
                        button.addClass('hide');
                    } else {
                        button.removeClass('hide');
                    }
                });
            }
        });

        // Refresh all widgets click event
        jQuery(document).on('click', '.' + refreshOpts.refreshAllClass, function() {
            dashboard.log('dashboardRefreshAllWidgets event thrown', 2);

            jQuery.each(dashboard.widgets, function (id, item) {
                item.refreshContent();
            });
        });

        // Layout change link click event
        jQuery(document).on('click', '.' + layoutOpts.openDialogClass, function() {
            dashboard.log('dashboardOpenLayoutDialog event thrown', 2);
            dashboard.element.trigger("dashboardOpenLayoutDialog");

            return false;
        });

        // Layout change dialog open method
        jQuery(document).on('dashboardOpenLayoutDialog', dashboard.element, function() {
            dashboard.log('Opening dialog ' + layoutOpts.dialogId, 1);

            var dialogElement = jQuery('#' + layoutOpts.dialogId);
            var layoutTemplate = jQuery('#' + layoutOpts.layoutTemplate);

            dialogElement.dialog('open');

            // add the layout images
            var h = dialogElement.find('.' + layoutOpts.layoutClass);

            h.empty();

            if (layoutTemplate.length == 0) {
                dashboard.log('Template "' + layoutOpts.layoutTemplate + ' not found', 5);
            } else if (h.children().length == 0) {
                dashboard.log('Number of layouts : ' + opts.layouts.length, 1);

                jQuery.each(opts.layouts, function (i, item) {
                    dashboard.log('Applying template : ' + layoutOpts.layoutTemplate, 1);

                    h.append(tmpl(layoutTemplate.html(), item));
                });
            }

            // Set the selected class for the selected layout
            jQuery('.' + layoutOpts.selectLayoutClass).removeClass(layoutOpts.selectedLayoutClass);
            jQuery('#' + dashboard.layout.id).addClass(layoutOpts.selectedLayoutClass);

            bindSelectLayout();
        });

        // Layout change dialog close method
        jQuery(document).on('dashboardCloseLayoutDialog', dashboard.element, function() {
            // close the dialog
            jQuery('#' + layoutOpts.dialogId).dialog('close');
        });

        // Dashboard state changed, ex. moved widgets etc.
        jQuery(document).on('dashboardStateChange', dashboard.element, function() {
            if (typeof opts.stateChangeUrl != 'undefined' && opts.stateChangeUrl != null && opts.stateChangeUrl != '') {
                jQuery.ajax({
                    url: opts.stateChangeUrl,
                    type: 'POST',
                    data: {
                        dashboard:  dashboard.element.attr("id"),
                        settings:   dashboard.serialize()
                    },
                    success: function(data) {
                        if (data.error) {
                            dashboard.log('dashboardStateChange event was', 1);

                            makeMessage(data.error, 'error', {timeout: 5000});
                        } else {
                            dashboard.log('dashboardSuccessfulSaved event thrown', 2);
                            dashboard.element.trigger("dashboardSuccessfulSaved");
                        }
                    }
                });
            }
        });


        // FIXME: why doesn't the live construct work in this case
        function bindSelectLayout() {
            var selectedLayout = jQuery('.' + layoutOpts.selectLayoutClass);

            if (selectedLayout.length == 0) {
                dashboard.log('Unable to find ' + layoutOpts.selectLayoutClass, 5);
            }

            selectedLayout.bind('click', function () {
                var currentLayout = dashboard.layout;

                dashboard.log('dashboardCloseLayoutDialog event thrown', 2);
                dashboard.element.trigger('dashboardCloseLayoutDialog');

                // Now set the new layout
                var newLayout = getLayout(jQuery(this).attr("id"));
                dashboard.layout = newLayout;

                // remove the class of the old layout
                if (typeof opts.layoutClass != 'undefined') {
                    dashboard.element.find('.' + opts.layoutClass).removeClass(currentLayout.classname).addClass(newLayout.classname);

                    fixSortableColumns();

                    // check if there are widgets in hidden columns, move them to the first column
                    if (jQuery('.' + opts.columnClass).length == 0) dashboard.log('Unable to find ' + opts.columnClass, 5);
                    dashboard.element.find('.nonsortablecolumn').each(function () {
                        // move the widgets to the first column
                        jQuery(this).children().appendTo(dashboard.element.find('.' + opts.columnClass + ':first'));

                        jQuery('.emptycolumn').remove();

                        // add the text to the empty columns
                        jQuery('.' + opts.columnClass).each(function () {
                            if (jQuery(this).children().length == 0) {
                                jQuery(this).html('<div class="emptycolumn">' + opts.emptyColumnHtml + '</div>');
                            }
                        });


                    });

                } else {
                    // set the new layout, but first move the dashboard to a temp
                    var temp = jQuery('<div style="display:none" id="tempdashboard"></div>');
                    temp.appendTo(jQuery("body"));

                    dashboard.element.children().appendTo(temp);

                    // reload the dashboard
                    dashboard.init();
                }

                // throw an event upon changing the layout.
                dashboard.log('dashboardChangeLayout event thrown', 2);
                dashboard.element.trigger('dashboardLayoutChanged');

            });
            return false;
        }

        // Category click
        jQuery(document).on('click', '.' + addOpts.selectCategoryClass, function() {
            dashboard.log('addWidgetDialogSelectCategory event thrown', 2);

            dashboard.element.trigger('addWidgetDialogSelectCategory', {"category": jQuery(this)});

            return false;
        });

        // Fetch category widgets
        //jQuery(document).on('addWidgetDialogSelectCategory', dashboard.element, function(e, obj) {
        jQuery(document).on('addWidgetDialogSelectCategory', function(e, obj) {
            var selectedCategory = jQuery('.' + addOpts.selectCategoryClass);
            var url = dashboard.widgetCategories[jQuery(obj.category).attr("id")];

            // remove the category selection
            selectedCategory.removeClass(addOpts.selectedCategoryClass).find('a i').removeClass('icon-white');

            // empty the widgets div
            jQuery('#' + addOpts.dialogId).find('.' + addOpts.widgetClass).empty();

            // select the category
            jQuery(obj.category).addClass(addOpts.selectedCategoryClass).find('a i').addClass('icon-white');

            dashboard.log('Getting JSON feed : ' + url, 1);

            // get the widgets
            jQuery.getJSON(url, {"cache": true}, function(json) {
                // load the widgets from the category
                if (json.result.data == 0) {
                    dashboard.log('Empty data returned', 3);
                }

                var items = json.result.data;

                if (typeof json.result.data.length == 'undefined') {
                    items = new Array(json.result.data);
                }

                jQuery.each(items, function (i, item) {
                    dashboard.log('Applying template : ' + addOpts.widgetTemplate, 1);

                    dashboard.widgetsToAdd[item.id] = item;

                    var widgetTemplate = jQuery('#' + addOpts.widgetTemplate);

                    if (widgetTemplate.length == 0) {
                        dashboard.log('Template "' + addOpts.widgetTemplate + ' not found', 5);
                    }

                    var html = tmpl(widgetTemplate.html(), item);
                    var widget = jQuery('#' + addOpts.dialogId).find('.' + addOpts.widgetClass);

                    widget.append(html);

                    if (dashboard.getWidget(item.id)) {
                        widget.find('button')
                            .addClass('disabled')
                            .removeClass('addwidget')
                            .html('<em>Widget already exists on dashboard</em>')
                        ;
                    }
                });
            });

            dashboard.log('addWidgetDialogWidgetsLoaded event thrown', 2);
            dashboard.element.trigger('addWidgetDialogWidgetsLoaded');
        });

        // Widget setup dialog content loaded
        jQuery(document).on('addWidgetDialogSetupsLoaded', function(e, widget, edit) {
            widgetSetupDialog.dialog('option', 'title', 'Configure of \''+ widget.title +'\' widget');
            widgetSetupDialog.dialog().data('widget', widget);

            var buttonsAll = {
                'close': {
                    text: 'Close',
                    class: 'btn nohide',
                    click: function() {
                        jQuery(this).dialog('close');
                    }
                }
            };

            var buttons = {};

            // Specify used buttons for widget edit and add
            if (edit) {
                buttons = {
                    'delete': {
                        text: 'Delete',
                        class: 'btn btn-danger pull-left',
                        click: function() {
                            jQuery(this).dialog('close');

                            jQuery('#'+ widget.id).trigger('widgetDelete', {"widget": widget, "edit": true});
                        }
                    },
                    'ok': {
                        text: 'Save widget',
                        class: 'btn btn-primary',
                        click: function() {
                            var form = jQuery(this).find('#widgetSetupForm');

                            form.validate().form();

                            if (form.validate().valid()) {
                                var widgetData = getWidgetData();
                                var widget = widgetSetupDialog.dialog().data('widget');

                                dashboard.element.trigger('dashboardSaveWidget', [widget, widgetData]);

                                jQuery(this).dialog('close');
                            }
                        }
                    }
                }
            } else {
                buttons = {
                    'back': {
                        text: 'Back to browse',
                        class: 'btn nohide',
                        click: function() {
                            var widgetData = widgetSetupDialog.dialog().data('widget');

                            jQuery(this).dialog('close');
                            jQuery(this).trigger('dashboardOpenWidgetDialog', [widgetData.category]);
                        }
                    },
                    'ok': {
                        text: 'Add widget',
                        class: 'btn btn-primary',
                        click: function() {
                            var form = jQuery(this).find('#widgetSetupForm');

                            form.validate().form();

                            if (form.validate().valid()) {
                                var widgetData = getWidgetData();
                                var widget = widgetSetupDialog.dialog().data('widget');

                                dashboard.element.trigger('dashboardAddWidget', [widget, widgetData]);

                                jQuery(this).dialog('close');
                            }
                        }
                    }
                }
            }

            widgetSetupDialog.dialog({buttons: jQuery.extend({}, buttonsAll, buttons)});
            widgetSetupDialog.dialog('open');
        });

        // TODO
        jQuery(document).on('dashboardAddWidget', function(e, widget, data) {
            data = (typeof data != 'undefined' ? data : {});

            if (typeof opts.widgetSaveUrl != 'undefined' && opts.widgetSaveUrl != null && opts.widgetSaveUrl != '') {
                data = (typeof data != 'undefined' ? data : {});

                jQuery.ajax({
                    url: opts.widgetSaveUrl,
                    type: 'POST',
                    data: {
                        type: 'insert',
                        widget: widget,
                        data: data
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            makeMessage(data.error, 'error', {timeout: 5000});
                        } else {
                            // Add new widget to dashboard
                            dashboard.addWidget(data, dashboard.element.find('.' + opts.columnPrefix + data.column));

                            // Trigger widgetShow -event to update actual content
                            jQuery('#'+ data.id).trigger('widgetShow', {"widget": dashboard.widgets[data.id]});
                        }
                    }
                });
            } else {
                alert('Widget save url not defined');
            }
        });

        // Save existing widget data
        jQuery(document).on('dashboardSaveWidget', function(e, widget, data) {
            if (typeof opts.widgetSaveUrl != 'undefined' && opts.widgetSaveUrl != null && opts.widgetSaveUrl != '') {
                data = (typeof data != 'undefined' ? data : {});

                jQuery.ajax({
                    url: opts.widgetSaveUrl,
                    type: 'POST',
                    data: {
                        type: 'update',
                        widget: widget.serialize(),
                        data: data
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            makeMessage(data.error, 'error', {timeout: 5000});
                        } else {
                            // Update widget data and refresh contents
                            jQuery.extend(widget, data);

                            widget.refreshContent();
                        }
                    }
                });
            } else {
                alert('Widget save url not defined');
            }
        });

        jQuery(document).on('click', '.' + addOpts.addWidgetClass, function() {
            var widget = dashboard.widgetsToAdd[jQuery(this).attr("id").replace('addwidget', '')];

            dashboard.log('dashboardCloseWidgetDialog event thrown', 2);
            dashboard.element.trigger('dashboardCloseWidgetDialog');

            dashboard.log('dashboardAddWidget event thrown', 2);

            if (typeof opts.widgetSetupUrl != 'undefined' && opts.widgetSetupUrl != null && opts.widgetSetupUrl != '') {
                var data = {
                    data: {},
                    widget: widget
                };

                widgetSetupDialog.empty();

                jQuery.ajax({
                    url: opts.widgetSetupUrl + widget.method,
                    type: 'POST',
                    data: data,
                    dataType: 'text',
                    success: function(data) {
                        widgetSetupDialog.html(data);

                        dashboard.element.trigger('addWidgetDialogSetupsLoaded', [widget, false]);
                    }
                });
            } else {
                alert('Widget setup url not defined');
            }

            return false;
        });

        jQuery(document).on('click', '.' + addOpts.openDialogClass, function() {
            dashboard.log('dashboardOpenWidgetDialog event thrown', 2);
            dashboard.element.trigger('dashboardOpenWidgetDialog');

            return false;
        });

        jQuery(document).on('dashboardCloseWidgetDialog', function() {
            jQuery('#' + addOpts.dialogId).dialog('close');
        });

        //jQuery(document).on('dashboardOpenWidgetDialog', dashboard.element, function(event, category) {
        jQuery(document).on('dashboardOpenWidgetDialog', function(event, category) {
            var dialog = jQuery('#' + addOpts.dialogId);

            //remove existing categories/widgets from the DOM, to prevent duplications
            dialog.find('.' + addOpts.categoryClass).empty();
            dialog.find('.' + addOpts.widgetClass).empty();

            dashboard.log('Opening dialog ' + addOpts.dialogId, 1);
            dashboard.log('Previous opened category: ' + category, 1);
            dialog.dialog('open');

            dashboard.log('Getting JSON feed : ' + addOpts.widgetDirectoryUrl, 1);

            jQuery.getJSON(addOpts.widgetDirectoryUrl, function (json) {
                if (json.category == 0) {
                    dashboard.log('Empty data returned', 3);
                }

                jQuery.each(json.categories.category, function (i, item) {
                    // Add the categories to the dashboard
                    dashboard.widgetCategories[item.id] = item.url;

                    dashboard.log('Applying template : ' + addOpts.categoryTemplate, 1);

                    var categoryTemplate = jQuery('#' + addOpts.categoryTemplate);

                    if (categoryTemplate.length == 0) {
                        dashboard.log('Template "' + addOpts.categoryTemplate + ' not found', 5);
                    }

                    var html = tmpl(categoryTemplate.html(), item);

                    dialog.find('.' + addOpts.categoryClass).append(html);
                });

                dashboard.log('addWidgetDialogCategoriesLoaded event thrown', 2);
                dashboard.element.trigger('addWidgetDialogCategoriesLoaded');

                // TODO determine which category to open

                dashboard.log('addWidgetDialogSelectCategory event thrown', 2);
                dashboard.element.trigger('addWidgetDialogSelectCategory', {"category": jQuery('#' + addOpts.dialogId).find('.' + addOpts.categoryClass + '>li:first')});
            });
        });

        return dashboard;
    };


    // Public static properties of dashboard. Default settings.
    jQuery.fn.dashboard.defaults = {
        debuglevel: 3,
        json_data: {},
        loadingHtml: '<div class="loading"><img alt="Loading, please wait" src="../themes/default/loading.gif" /><p>Loading...</p></div>',
        emptyColumnHtml: 'Drag your widgets here',
        widgetTemplate: 'widgettemplate',
        columnPrefix: 'column-',
        opacity: "0.2",
        deleteConfirmMessage: "Are you sure you want to delete this widget?",
        widgetNotFoundHtml: "The content of this widget is not available anymore. You may remove this widget.",
        columnClass: 'column',
        widgetClass: 'widget',
        menuClass: 'controls_',
        widgetContentClass: 'widgetcontent',
        widgetTitleClass: 'widgettitle',
        widgetHeaderClass: 'widgetheader',
        widgetFullScreenClass: 'widgetopenfullscreen',
        iconsClass: 'icons',
        stateChangeUrl: '',
        widgetSaveUrl: '',
        widgetSetupUrl: '',
        addWidgetSettings: {
            openDialogClass: 'openaddwidgetdialog',
            addWidgetClass: 'addwidget',
            selectCategoryClass: 'selectcategory',
            selectedCategoryClass: 'selected',
            categoryClass: 'categories',
            widgetClass: 'widgets',
            dialogId: 'addwidgetdialog',
            categoryTemplate: 'categorytemplate',
            widgetTemplate: 'addwidgettemplate'
        },
        editLayoutSettings: {
            dialogId: 'editLayout',
            layoutClass: 'layoutselection',
            selectLayoutClass: 'layoutchoice',
            selectedLayoutClass: 'selected',
            openDialogClass: 'editlayout',
            layoutTemplate: 'selectlayouttemplate'
        },
        refreshSettings: {
            refreshAllClass: 'widgetRefreshAll',
            refreshSingleClass: 'widgetRefreshThis'
        }
    };

    // Default widget settings.
    jQuery.fn.dashboard.widget = {
        defaults: {
            open: true,
            fullscreen: false,
            loaded: false,
            url: '',
            metadata: {},
            refresh: 0
        }
    };

})(jQuery); // end of closure


// Simple JavaScript Templating
// John Resig - http://ejohn.org/ - MIT Licensed
(function () {
    var cache = {};

    this.tmpl = function tmpl(str, data) {
        // Figure out if we're getting a template, or if we need to
        // load the template - and be sure to cache the result.

        var fn = !/\W/.test(str) ?
            cache[str] = cache[str] ||
                tmpl(document.getElementById(str).innerHTML) :

            // Generate a reusable function that will serve as a template
            // generator (and which will be cached).
            new Function("obj",
                "var p=[],print=function(){p.push.apply(p,arguments);};" +

                    // Introduce the data as local variables using with(){}
                    "with(obj){p.push('" +

                    // Convert the template into pure JavaScript
                    str
                        .replace(/[\r\t\n]/g, " ")
                        .split("<%").join("\t")
                        .replace(/((^|%>)[^\t]*)'/g, "$1\r")
                        .replace(/\t=(.*?)%>/g, "',$1,'")
                        .split("\t").join("');")
                        .split("%>").join("p.push('")
                        .split("\r").join("\\'")
                    + "');}return p.join('');");

        // Provide some basic currying to the user
        return data ? fn(data) : fn;
    };
})();
