$(document).ready(function() {

    // load the templates
    jQuery('body').append('<div id="templates"></div>');

    var templates = jQuery('#templates');

    templates.hide();
    templates.load(pageBaseHref + "Dashboard/GetTemplates", initDashboard);

    // call for the minimal dashboard
    function initDashboard() {
        var dashboard = $('#dashboard').dashboard({
            json_data : {
                url: pageBaseHref + "Dashboard/GetMyWidgets"
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
        dashboard.init();
    }
});