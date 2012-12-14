<div id="widgetClock">
    <div class="time">
        {$smarty.now|date_format:"%H:%M:%S"}
    </div>
    <div class="date">
        {$smarty.now|date_format:"%Y.%m:%d"}
    </div>
</div>

<script type="text/javascript">
    setInterval(function() {
        var date = new Date();

        var hours = date.getHours() < 10 ? "0"+ date.getHours() : date.getHours();
        var minutes = date.getMinutes() < 10 ? "0"+ date.getMinutes() : date.getMinutes();
        var seconds = date.getSeconds() < 10 ? "0"+ date.getSeconds() : date.getSeconds();
        var day = date.getDate() < 10 ? "0"+ date.getDate() : date.getDate();
        var month = (date.getMonth() + 1) < 10 ? "0"+ (date.getMonth() + 1) : (date.getMonth() + 1);
        var year = date.getFullYear();

        var container = jQuery('#widgetClock');

        jQuery('div.time', container).text(hours +':'+ minutes +':'+ seconds);
        jQuery('div.date', container).text(year +'.'+ month +'.'+ day);

    }, 1000);
</script>