<div id="widgetSetupNotFound" class="widgetSetup" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
    <div class="alert alert-error">
        <h4>Error!</h4>
        <p>
            Setup method <em>'{$methodName}'</em> not found for this widget! Please implement setup method to following class:
        </p>

        <pre class="pre-scrollable">{$className}</pre>

        <p>
            Basically this method <em>must</em> be declared as following:
        </p>

        <pre class="pre-scrollable">public function {$methodName}(array $widget, array $data)
{
    \\ Implement your widget setup controller method...
}</pre>
    </div>

    <div class="alert alert-info">
        <h4>Setup method parameter data</h4>
        <p>
            Method parameter <em>'$widget'</em> contents:
        </p>
        <pre class="pre-scrollable text-mini">{$widget|print_r:true|trim} </pre>

        <p>
            Method parameter <em>'$data'</em> contents:
        </p>
        <pre class="pre-scrollable text-mini">{$data|print_r:true|trim} </pre>
    </div>
</div>

<script type="text/javascript">
    jQuery(function () {
        var dialogContainer = jQuery('#widgetSetupNotFound').parent().parent();
        var buttonContainer = dialogContainer.find('div.ui-dialog-buttonset');
console.log('jee');
        buttonContainer.find('button').each(function() {
            var button = jQuery(this);

            if (!button.hasClass('nohide')) {
                jQuery(this).addClass('hide');
            }
        });
    });
</script>