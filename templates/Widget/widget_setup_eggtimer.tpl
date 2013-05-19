<div id="widgetSetup">
    <ul class="nav nav-tabs nav-tabs-white" id="widgetSetupNavigation">
        <li class="active"><a href="#widgetSetupTabBasic">Basic settings</a></li>
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
        </div>
    </form>
</div>

<script type="text/javascript">
    {literal}
    function getWidgetValidationRules() {
        return getWidgetValidationRulesDefault();
    }

    function getWidgetData() {
        return getWidgetDataDefault();
    }

    jQuery(function () {
        var container = jQuery('#widgetSetup');
        var navigation = container.find('#widgetSetupNavigation');

        navigation.find('a').click(function (e) {
            e.preventDefault();

            jQuery(this).tab('show');
        });
    });
    {/literal}
</script>
