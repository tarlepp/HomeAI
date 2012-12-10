<!DOCTYPE HTML>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{$pageTitle}</title>

    <base href="{$pageBaseHref}"/>

    <meta name="author" content="Tarmo Leppänen"/>
    <meta name="keywords" content="{foreach from=$pageKeywords item=keyword}{$keyword},{/foreach}"/>
    <meta name="description" content="{if empty($pageDescription)}HomeAI see https://github.com/tarlepp/HomeAI{else}{$pageDescription}{/if}"/>
    <meta name="verify-v1" content="{$googleVerifyCode}"/>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

    <link rel="icon" href="{$pageBaseHref}images/layout/favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" href="{$pageBaseHref}images/layout/favicon.ico" type="image/x-icon"/>

{$pageJavascript}
{$pageCss}

{literal}
    <!--[if !IE]>
    <style type="text/css">
    </style>
    <![endif]-->

    <!--[if lt IE 9]>
    <script type="text/javascript">
    </script>
    <![endif]-->
{/literal}

    <script type="text/javascript">
        var pageBaseHref = '{$pageBaseHref}';

        {if is_array($pageScript)}
            {literal}jQuery(function() {{/literal}
            {foreach from=$pageScript key=key item=script}
                {$script}
            {/foreach}
            {literal}});{/literal}
        {/if}
    </script>

</head>
<body>


{$pageHeader}
{$pageNavigation}
{$pageContent}
{$pageFooter}


{if !empty($googleAnalyticsCode)}
<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', '{$googleAnalyticsCode}']);
    _gaq.push(['_trackPageview']);

{literal}
    (function () {
        var ga = document.createElement('script');
        ga.type = 'text/javascript';
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ga, s);
    })();
{/literal}

</script>
{/if}

</body>
</html>
