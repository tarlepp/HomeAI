<div id="widgetSetup">
    <ul class="nav nav-tabs nav-tabs-white" id="widgetSetupNavigation">
        <li class="active"><a href="#widgetSetupTabBasic">Basic settings</a></li>
        <li class=""><a href="#widgetSetupTabCurl">cURL settings</a></li>
        <li class=""><a href="#widgetSetupTabOutput">Output</a></li>
    </ul>

    {foreach from=$metadata key=key item=item}
        {if isset($data.metadata.data.{$item})}
            {assign var=$key value=$data.metadata.data.{$item}}
            {else}
            {assign var=$key value=null}
        {/if}
    {/foreach}

    <form id="widgetSetupForm" class="form-horizontal row-fluid">
        <div class="tab-content">

            <div id="widgetSetupTabBasic" class="tab-pane active">
                {include file='widget_setup_common.tpl' data=$data widget=$widget}
            </div>

            <div id="widgetSetupTabCurl" class="tab-pane">
                <div class="control-group">
                    <label class="control-label span3">URL</label>
                    <div class="controls span9">
                        <input type="text" name="url" class="span9" required placeholder="Add URL to fetch..." value="{$_url}"/>
                        <span class="help-block"></span>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label span3">Request type</label>
                    <div class="controls span9">
                        <select id="widgetSetupCurlType" name="type" class="span9">
                            <option value="GET"{if $_type === 'GET'} selected="selected"{/if}>GET</option>
                            <option value="POST"{if $_type === 'POST'} selected="selected"{/if}>POST</option>
                        </select>
                        <span class="help-block"></span>
                    </div>
                </div>

                <div id="postDataContainer" class="{if $_type !== 'POST'}hide{/if}">
                 {foreach from=$_postData key=key item=item name=postData}
                     <div class="control-group">
                     {if $smarty.foreach.postData.index == 0}
                        <label class="control-label span3" style="margin-bottom: 0;">Post data</label>
                        <div class="controls span9 input-append inline-inputs">
                     {else}
                         <div class="controls span9 offset3 input-append inline-inputs">
                     {/if}
                             <input data-type="key" type="text" name="data[key][]" class="span4" placeholder="Key" value="{$key}" />
                             <input data-type="value" type="text" name="data[value][]" class="span6 no-left-border-radious" placeholder="Value" value="{$item}" />
                        {if $smarty.foreach.postData.index == 0}
                             <button data-add="true" class="btn" type="button">+</button>
                        {else}
                            <button data-remove="true" data-dialog-close="false" class="btn" type="button">-</button>
                        {/if}
                         </div>
                     </div>
                 {foreachelse}
                     <div class="control-group">
                         <label class="control-label span3" style="margin-bottom: 0;">Post data</label>
                         <div class="controls span9 input-append inline-inputs">
                             <input data-type="key" type="text" name="data[key][]" class="span4" placeholder="Key" />
                             <input data-type="value" type="text" name="data[value][]" class="span6 no-left-border-radious" placeholder="Value" />
                             <button data-add="true" class="btn disabled" type="button">+</button>
                         </div>
                     </div>
                 {/foreach}
                </div>

                <div id="headers">
                {foreach from=$_headers key=key item=item name=headers}
                    <div class="control-group">
                    {if $smarty.foreach.headers.index == 0}
                        <label class="control-label span3" style="margin-bottom: 0;">Request headers</label>
                        <div class="controls span9 input-append">
                    {else}
                        <div class="controls span9 offset3 input-append">
                    {/if}
                            <input type="text" name="headers[]" class="span9" placeholder="Add custom header value for request..." value="{$item}" />
                        {if $smarty.foreach.headers.index == 0}
                            <button data-add="true" class="btn" type="button">+</button>
                        {else}
                            <button data-remove="true" data-dialog-close="false" class="btn" type="button">-</button>
                        {/if}
                        </div>
                    </div>
                {foreachelse}
                    <div class="control-group">
                        <label class="control-label span3" style="margin-bottom: 0;">Request headers</label>
                        <div class="controls span9 input-append">
                            <input type="text" name="headers[]" class="span9" placeholder="Add custom header value for request..."/>
                            <button data-add="true" class="btn disabled" type="button">+</button>
                        </div>
                    </div>
                {/foreach}
                </div>

                <div class="control-group">
                    <div class="controls span9 offset3">
                        <button id="testCurlRequest" class="btn disabled" type="button">Test current configuration</button>
                    </div>
                </div>
            </div>

            <div id="widgetSetupTabOutput" class="tab-pane">
                <pre class="pre-scrollable"><em>Current cURL configuration not yet tested...</em></pre>
            </div>
        </div>
    </form>

    <div id="widgetSetupTemplates" class="hide">
        <div id="widgetSetupHeadersTemplate">
            <div class="control-group">
                <div class="controls span9 offset3 input-append">
                    <input type="text" name="headers[]" class="span9" placeholder="Add custom header value for request..." />
                    <button data-remove="true" data-dialog-close="false" class="btn" type="button">-</button>
                </div>
            </div>
        </div>

        <div id="widgetSetupPostDataTemplate">
            <div class="control-group">
                <div class="controls span9 offset3 input-append inline-inputs">
                    <input data-type="key" type="text" name="data[key][]" class="span4" placeholder="Key" />
                    <input data-type="value" type="text" name="data[value][]" class="span6 no-left-border-radious" placeholder="Value" />
                    <button data-remove="true" data-dialog-close="false" class="btn" type="button">-</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    {literal}
    function getWidgetValidationRules() {
        var options = getWidgetValidationRulesDefault();

        var rules = {
            url: {
                required: true,
                url: true
            },
            type: {
                required: true
            }
        };

        jQuery.extend(options.rules, rules);

        return options;
    }


    function getWidgetData(validate) {
        var container = jQuery('#widgetSetup');
        var form = container.find('form');
        var output = getWidgetDataDefault(validate);
        var metadata = {
            type: 'curl',
            data: {}
        };

        var elements = {
            url: {
                selector: 'input[name=url]',
                type: 'text'
            },
            type: {
                selector: 'select[name=type]',
                type: 'select'
            },
            headers: {
                selector: 'input[name="headers[]"]',
                type: 'header'
            },
            postData: {
                selector: 'input[name="data[key][]"]',
                type: 'data'
            }
        };

        jQuery.each(elements, function(key, data) {
            var element = jQuery(data.selector, form);
            var row = element.parent().parent();
            var value = null;
            var valueOrg = element.val();
            var errors = false;

            switch (data.type) {
                case 'text':
                    value = jQuery.trim(element.val());
                    break;
                case 'select':
                    value = jQuery.trim(element.val());
                    break;
                case 'header':
                    value = [];

                     element.each(function() {
                        value.push(jQuery(this).val());
                     });
                    break;
                case 'data':
                    value = {};

                    element.each(function() {
                        var key = jQuery(this).val();

                        value[key] = jQuery(this).next().val();
                    });
                    break;
                default:
                    return true;
                    break;
            }

            // Store current value
            metadata['data'][key] = value;

            return true;
        });

        console.log(metadata);

        return jQuery.extend({}, output, {metadata: metadata});
    }

    jQuery(function () {
        var container = jQuery('#widgetSetup');
        var navigation = jQuery('#widgetSetupNavigation');
        var tabBasic = container.find('#widgetSetupTabBasic');
        var tabSetup = container.find('#widgetSetupTabBasic');
        var tabOutput = container.find('#widgetSetupTabOutput');
        var templates = container.find('#widgetSetupTemplates');
        var form = container.find('form');
        var button = form.find('#testCurlRequest');

        var postDataContainer = form.find('#postDataContainer');

        navigation.find('a').click(function (e) {
            e.preventDefault();

            jQuery(this).tab('show');
        });

        if (form.find('input[name=url]').val().length > 0) {
            button.removeClass('disabled');
        }

        var firstHeader = form.find('#headers input').filter(':first');

        if (firstHeader.val().length > 0) {
            firstHeader.parent().find('button[data-add="true"]').removeClass('disabled');
        }

        form.on('change', '#widgetSetupCurlType', function() {
            switch (jQuery('#widgetSetupCurlType option:selected', form).val()) {
                case 'GET':
                    postDataContainer.addClass('hide');
                    break;
                case 'POST':
                    postDataContainer.removeClass('hide');
                    break;
            }
        });

        form.on('keyup', 'input[name=url]', function() {
            var value = jQuery.trim(jQuery(this).val());

            if (value.length > 0) {
                button.removeClass('disabled');
            } else {
                button.addClass('disabled');
            }
        });

        form.find('#headers').on('keyup', 'input', function() {
            var value = jQuery.trim(jQuery(this).val());
            var button = jQuery(this).parent().find('button[data-add="true"]');

            if (value.length > 0) {
                button.removeClass('disabled');
            } else {
                button.addClass('disabled');
            }
        });

        form.find('#headers').on('click', 'button[data-add="true"]', function() {
            if (!jQuery(this).hasClass('disabled')) {
                var newRow = templates.find('#widgetSetupHeadersTemplate').clone();

                newRow.appendTo(jQuery(this).parent().parent().parent());
            }
        });

        form.find('#headers').on('click', 'button[data-remove="true"]', function(e) {
            var row = jQuery(this).closest('.control-group');

            row.hide();

            setTimeout(function() {
                row.remove();
            }, 100);
        });

        form.find('#postDataContainer').on('keyup', 'input', function() {
            var element = jQuery(this);
            var value = jQuery.trim(element.val());
            var button = element.parent().find('button[data-add="true"]');
            var type = element.data('type');
            var parallelElement = null;

            if (type == 'key') {
                parallelElement = element.parent().find('input[data-type="value"]');
            } else {
                parallelElement = element.parent().find('input[data-type="key"]');
            }

            var canHide = parallelElement.length <= 0;

            if (value.length > 0) {
                button.removeClass('disabled');
            } else if (value.length == 0 && canHide) {
                button.addClass('disabled');
            }
        });

        form.find('#postDataContainer').on('click', 'button[data-add="true"]', function() {
            if (!jQuery(this).hasClass('disabled')) {
                var newRow = templates.find('#widgetSetupPostDataTemplate').clone();

                newRow.appendTo(jQuery(this).parent().parent().parent());
            }
        });

        form.find('#postDataContainer').on('click', 'button[data-remove="true"]', function() {
            var row = jQuery(this).closest('.control-group');

            row.hide();

            setTimeout(function() {
                row.remove();
            }, 100);
        });

        form.on('click', '#testCurlRequest', function() {
            if (jQuery(this).hasClass('disabled')) {
                return false;
            }

            var loading = '<div class="loading"></div>';
            var data = getWidgetData();

            jQuery.ajax({
                url: '{/literal}{$widget.url}{literal}',
                data: data.metadata.data,
                dataType: 'text',
                beforeSend: function() {
                    navigation.find('a[href="#widgetSetupTabOutput"]').tab('show');
                    tabOutput.find('pre').html(loading);
                },
                success: function(data) {
                    tabOutput.find('pre').html(jQuery('<div/>').text(data).html());
                }
            });

            return true;
        });
    });
    {/literal}
</script>
