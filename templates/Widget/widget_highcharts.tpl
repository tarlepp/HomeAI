<div id="{$id}" class="widgetHighcharts {$options.class}"></div>

<script type="text/javascript">
    jQuery(function () {
        var chart;

        jQuery(document).ready(function() {
            chart = new Highcharts.Chart({$config});
        });
    });
</script>