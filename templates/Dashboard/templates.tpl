<script type="text/html" id="categorytemplate">
    <li id="<%= id %>" class="selectcategory" data-category="<%= title %>">
        <a href="#"><%= title %> ( <%= amount %> ) <i class="icon-chevron-right pull-right"></i></a>
    </li>
</script>


<script type="text/html" id="widgettemplate">
    <div class="ui-widget ui-corner-all ui-widget-content widget" id="<%= id %>" title="<%= title %>">
        <div class="navbar">
            <div class="ui-widget-header ui-corner-all widgetheader navbar-inner">
                <span class="widgettitle"><%= title %></span>
                <ul class="nav pull-right dropdown" role="navigation">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-pencil"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#" class="controls" data-control="widgetRefresh"><i class="icon-refresh"></i>Refresh content</a></li>
                            <li><a href="#" class="controls" data-control="widgetEdit"><i class="icon-edit"></i>Edit settings</a></li>
                            <li><a href="#" class="controls" data-control="widgetDelete"><i class="icon-remove"></i>Remove this widget</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

        <div class="widgetcontent"></div>
    </div>
</script>

<script type="text/html" id="widgetsetuptemplate">
    <div id="widgetsetupcontainer class="widgetSetup">
    </div>
</script>

<script type="text/html" id="selectlayouttemplate">
    <li class="layoutchoice" id="<%= id %>" style="background-image: url('<%= image %>')"></li>
</script>


<script type="text/html" id="addwidgettemplate">
    <li class="widgetitem">
        <div class="row-fluid">
            <div class="span3">
                <img src="<%= image %>" alt="" height="50" width="100" class="img-rounded img-polaroid">
            </div>
            <div class="span9">
                <h3><%= title %></h3>
                <p class="muted">By <%= creator %></p>
                <p>
                    <%= description %>
                    <button class="macro-button-add addwidget btn btn-primary pull-right" id="addwidget<%= id %>">Add this</button>
                    <input class="macro-hidden-uri" value="<%= url %>" type="hidden" />
                </p>
            </div>
        </div>
    </li>
</script>

<div id="widgetSetupDialog" class="dialog">
</div>



<div class="dialog" id="addwidgetdialog" title="Widget Directory">
    <div class="row-fluid">
        <div class="span3">
            <ul class="categories nav nav-tabs nav-stacked">
            </ul>
        </div>

        <!--<div class="panel-body">-->
        <div class="span9">
            <ol id="category-all" class="widgets">
            </ol>
        </div>
    </div>
</div>


<div class="dialog" id="editLayout" title="Choose dashboard layout">
    <div class="panel-body" id="layout-dialog">
        <ul class="layoutselection">
        </ul>
    </div>
</div>

