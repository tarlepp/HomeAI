<div class="widgetRss">
    <ul>
        {foreach from=$items item=item}
            <li>
                <a href="{$item->get_permalink()}" rel="external" target="_blank" class="tooltipDiv">
                    {$item->get_title()}
                    {if $item->get_description()}
                    <div class="tooltipDivContainer">
                        <h1></h1>
                        <div>
                            {$item->get_description()}
                        </div>
                    </div>
                    {/if}
                </a>
                <time class="timeago" datetime="{$item->get_date()}">{$item->get_date()}</time>
            </li>
        {foreachelse}
            <li>
                RSS Feed doesn't contain items.
            </li>
        {/foreach}
    </ul>
</div>

<script type="text/javascript">
    jQuery(function () {
        jQuery(".widgetRss time.timeago").timeago();
    });
</script>