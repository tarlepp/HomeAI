
{foreach from=$data item=item}
    {if !empty($item.title)}
        var message = "<h1>{$item.title}</h1>{$item.message}";
    {else}
        var message = "{$item.message}";
    {/if}

    makeMessage(message, '{$type}', { timeout: 5000, dismissQueue: true });

{/foreach}
