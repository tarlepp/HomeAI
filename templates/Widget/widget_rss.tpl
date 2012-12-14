<div class="widgetRss">
    <ul>
        {foreach from=$items item=item}
            <li>
                <a href="{$item->get_permalink()}" class="tooltipDiv">
                    {$item->get_title()}
                    <div class="tooltipDivContainer">
                        <h1></h1>
                        <div>
                            {$item->get_description()}
                        </div>
                    </div>
                </a>
                <span>{$item->get_date()}</span>
            </li>
        {/foreach}
    </ul>
</div>