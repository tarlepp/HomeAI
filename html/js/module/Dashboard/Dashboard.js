// This is for widgets intervals, this is used if widget has refresh time specified
var widgetIntervals = [];

// This is for widget internal time intervals, see usage from example_live.json
var widgetInternalIntervals = [];

jQuery(document).ready(function() {
    // load the templates
    jQuery('body').append('<div id="templates"></div>');

    var templates = jQuery('#templates');

    templates.hide();
    templates.load(pageBaseHref + "Dashboard/GetTemplates", initDashboard);

    // call for the minimal dashboard
    function initDashboard() {
        var dashboard = jQuery("#dashboard").dashboard({
            widgetSaveUrl: pageBaseHref + 'Widget/Save/',
            widgetSetupUrl: pageBaseHref + 'Widget/Setup/',
            stateChangeUrl: pageBaseHref + 'Dashboard/Update/',
            json_data : {
                url: pageBaseHref + 'Dashboard/GetWidgets'
            },
            refreshSettings: {
            },
            editLayoutSettings: {
            },
            addWidgetSettings: {
                openDialogClass: "widgetEdit",
                widgetDirectoryUrl: pageBaseHref + "Widget/GetCategories",
                addWidgetClass:'addwidget',
                selectCategoryClass:'selectcategory',
                selectedCategoryClass:'active',
                categoryClass:'categories',
                widgetClass:'widgets',
                dialogId:'addwidgetdialog',
                categoryTemplate:'categorytemplate',
                widgetTemplate:'addwidgettemplate'
            },
            loadingHtml: '<div class="loading"></div>',
            layouts :
                [
                    { title: "Layout1",
                        id: "layout1",
                        image: pageBaseHref +"images/layouts/layout1.png",
                        html: '<div class="layout layout-a"><div data-column="first" class="column first column-first"></div></div>',
                        classname: 'layout-a'
                    },
                    { title: "Layout2",
                        id: "layout2",
                        image: pageBaseHref +"images/layouts/layout2.png",
                        html: '<div class="layout layout-aa"><div data-column="first" class="column first column-first"></div><div data-column="second" class="column second column-second"></div></div>',
                        classname: 'layout-aa'
                    },
                    { title: "Layout3",
                        id: "layout3",
                        image: pageBaseHref +"images/layouts/layout3.png",
                        html: '<div class="layout layout-ba"><div data-column="first" class="column first column-first"></div><div data-column="second" class="column second column-second"></div></div>',
                        classname: 'layout-ba'
                    },
                    { title: "Layout4",
                        id: "layout4",
                        image: pageBaseHref +"images/layouts/layout4.png",
                        html: '<div class="layout layout-ab"><div data-column="first" class="column first column-first"></div><div data-column="second" class="column second column-second"></div></div>',
                        classname: 'layout-ab'
                    },
                    { title: "Layout5",
                        id: "layout5",
                        image: pageBaseHref +"images/layouts/layout5.png",
                        html: '<div class="layout layout-aaa"><div data-column="first" class="column first column-first"></div><div data-column="second" class="column second column-second"></div><div data-column="third" class="column third column-third"></div></div>',
                        classname: 'layout-aaa'
                    }
                ]
        });

        var dashboardWidget = jQuery('.widget');
        var loading = '<div class="loading"></div>';

        jQuery(document).on("widgetShow widgetOpen widgetClose widgetAdded widgetRefresh widgetDelete", '.widget', function(e, o) {
            //console.log(o.widget.id +" - event - "+ e.type);

            var widget = jQuery('#'+ o.widget.id);
            var intervalId = 'interval' + o.widget.id;
            var parameters = jQuery.extend(
                {},
                o.widget.metadata.data,
                {
                    widgetData: {
                        id: o.widget.id,
                        interval: intervalId
                    }
                }
            );

            switch (e.type) {
                case 'widgetShow':
                    clearInterval(widgetIntervals[intervalId]);

                    var refreshInterval = parseInt(o.widget.refresh, 10);

                    if (refreshInterval) {
                        widgetIntervals[intervalId] = setInterval(function() {
                            o.widget.refreshContentSilently();
                        }, refreshInterval * 1000);
                    }

                    // Remove widget title, this is annoying
                    jQuery(this).find('.widgetcontent').parent().attr('title', '');

                    switch (o.widget.metadata.type) {
                        case 'curl':
                            handleRequest(pageBaseHref +'Widget/Curl', widget, parameters);
                            break;
                        case 'rss':
                            handleRequest(pageBaseHref +'Widget/Rss', widget, parameters);
                            break;
                        case 'highcharts':
                            handleRequest(pageBaseHref +'Widget/Highcharts', widget, parameters);
                            break;
                    }
                    break;
                case 'widgetClose':
                case 'widgetDelete':
                    clearInterval(widgetIntervals[intervalId]);
                    break;
            }
        });

        function handleRequest(url, widget, parameters) {
            jQuery.ajax({
                url: url,
                data: parameters,
                dataType: 'text',
                beforeSend: function(){
                    widget.find('.widgetcontent').html(loading);
                },
                success: function(data) {
                    widget.find('.widgetcontent').html(data);
                    widget.find('.dropdown-toggle').dropdown();
                }
            });
        }

        dashboard.init();
    }
});


