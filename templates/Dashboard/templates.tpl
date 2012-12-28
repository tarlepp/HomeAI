<script type="text/html" id="categorytemplate">
    <li id="<%= id %>" class="selectcategory">
        <button><%= title %> (<%= amount %>)</button>
    </li>
</script>


<script type="text/html" id="widgettemplate">
    <div class="ui-widget ui-corner-all ui-widget-content widget" id="<%= id %>" title="<%= title %>">
        <div class="ui-widget-header ui-corner-all widgetheader">
            <span class="widgettitle"><%= title %></span>
            <span class="right icons hidden">
                <span class="controls tooltipTitle" title="Refresh widget content" data-control="widgetRefresh"><i class="icon-refresh"></i></span>
                <span class="controls tooltipTitle" title="Edit widget settings" data-control="widgetEdit"><i class="icon-edit"></i></span>
                <span class="controls tooltipTitle" title="Remove this widget" data-control="widgetDelete"><i class="icon-remove"></i></span>
            </span>
        </div>
        <div class="widgetcontent">
        </div>
    </div>
</script>


<script type="text/html" id="selectlayouttemplate">
    <li class="layoutchoice" id="<%= id %>" style="background-image: url('<%= image %>')"></li>
</script>


<script type="text/html" id="addwidgettemplate">
    <li class="widgetitem">
        <img src="<%= image %>" alt="" height="60" width="120">

        <div class="add-button">
            <input class="macro-button-add addwidget" id="addwidget<%= id %>" value="Add it Now" type="button"><br>
            <input class="macro-hidden-uri" value="<%= url %>" type="hidden">
        </div>
        <!-- // .add-button -->
        <h3><a href=""><%= title %></a></h3>

        <p>By <%= creator %></p>

        <p><%= description %></p>
    </li>
</script>


<div class="dialog" id="addwidgetdialog" title="Widget Directory">
    <ul class="categories">
    </ul>

    <div class="panel-body">
        <ol id="category-all" class="widgets">
        </ol>
    </div>
</div>


<div class="dialog" id="editLayout" title="Choose dashboard layout">
    <div class="panel-body" id="layout-dialog">
        <ul class="layoutselection">
        </ul>
    </div>
</div>
