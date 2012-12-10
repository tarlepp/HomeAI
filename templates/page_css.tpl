{foreach from=$data key=stylesheet item=media}
        <link href="{$pageBaseHref}css/{$stylesheet}" rel="stylesheet" type="text/css" media="{$media}" />
{/foreach}