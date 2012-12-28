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
        var dashboard = jQuery('#dashboard').dashboard({
            json_data : {
                url: pageBaseHref + "Dashboard/GetMyWidgets"
            },
            refreshSettings: {

            },
            editLayoutSettings: {

            },
            loadingHtml: '<div class="loading"></div>',
            layouts :
                [
                    { title: "Layout1",
                        id: "layout1",
                        image: pageBaseHref +"images/layouts/layout1.png",
                        html: '<div class="layout layout-a"><div class="column first column-first"></div></div>',
                        classname: 'layout-a'
                    },
                    { title: "Layout2",
                        id: "layout2",
                        image: pageBaseHref +"images/layouts/layout2.png",
                        html: '<div class="layout layout-aa"><div class="column first column-first"></div><div class="column second column-second"></div></div>',
                        classname: 'layout-aa'
                    },
                    { title: "Layout3",
                        id: "layout3",
                        image: pageBaseHref +"images/layouts/layout3.png",
                        html: '<div class="layout layout-ba"><div class="column first column-first"></div><div class="column second column-second"></div></div>',
                        classname: 'layout-ba'
                    },
                    { title: "Layout4",
                        id: "layout4",
                        image: pageBaseHref +"images/layouts/layout4.png",
                        html: '<div class="layout layout-ab"><div class="column first column-first"></div><div class="column second column-second"></div></div>',
                        classname: 'layout-ab'
                    },
                    { title: "Layout5",
                        id: "layout5",
                        image: pageBaseHref +"images/layouts/layout5.png",
                        html: '<div class="layout layout-aaa"><div class="column first column-first"></div><div class="column second column-second"></div><div class="column third column-third"></div></div>',
                        classname: 'layout-aaa'
                    }
                ]
        });

        var dashboardWidget = jQuery('.widget');
        var loading = '<div class="loading"></div>';

        dashboardWidget.live({
            'widgetShow': function(event, object) {
                console.log('show widget: '+ object.widget.id);

                var intervalId = 'interval' + object.widget.id;
                var parameters = jQuery.extend(
                    {},
                    object.widget.metadata.data,
                    {
                        widgetData: {
                            id: object.widget.id,
                            interval: intervalId
                        }
                    }
                );

                // Remove widget title, this is annoying
                jQuery(this).find('.widgetcontent').parent().attr('title', '');

                switch (object.widget.metadata.type) {
                    case 'curl':
                        handleRequest(pageBaseHref +'Widget/Curl', jQuery(this), parameters);
                        break;
                    case 'rss':
                        handleRequest(pageBaseHref +'Widget/Rss', jQuery(this), parameters);
                        break;
                    case 'highcharts':
                        handleRequest(pageBaseHref +'Widget/Highcharts', jQuery(this), parameters);
                        break;
                }
            },
            'widgetOpen': function(event, object) {
                console.log('open widget: '+ object.widget.id);
            },
            'widgetClose': function(event, object){
                console.log('close widget: '+ object.widget.id);
            },
            'widgetAdded': function(event, object) {
                console.log('added widget: '+ object.widget.id);
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
                }
            });
        }

        dashboard.init();
    }

    var sideMenu = jQuery('#sideMenu');

    sideMenu.find('div').on('mouseenter', function() {
        jQuery(this).find('span').hide();

        sideMenu.find('ul').show();
    });

    sideMenu.find('div').on('mouseleave', function() {
        jQuery(this).find('span').show();

        sideMenu.find('ul').hide();
    });

    sideMenu.find('a').on('click', function() {
        sideMenu.find('ul').hide();
    });
});