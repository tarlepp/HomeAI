<ul class="nav">
    <li class="active">
        <a href="{$pageBaseHref}Dashboard/">Dashboard</a>
    </li>
    {if $authStatus}
    {/if}
</ul>

<ul class="nav pull-right">
    {if $authStatus}
        <li><a id="userProfileLink" href="#">{$authData.firstname} {$authData.surname}</a></li>
        <li><a id="logoutLink" href="Auth/Logout">Logout</a></li>
    {else}
        <li><a id="loginLink" href="#">Login</a></li>
    {/if}
</ul>