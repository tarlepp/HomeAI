<div class="control-group">
    <label class="control-label span3">Widget title</label>
    <div class="controls span9">
        <input type="text" name="title" class="span9" placeholder="Input widget title" />
        <span class="help-block"></span>
    </div>
</div>

<div class="control-group">
    <label class="control-label span3">Column where to add</label>
    <div class="controls span9">
        <select name="column" class="span9">
        </select>
        <span class="help-block hide"></span>
    </div>
</div>

{if $widget.refreshable == 'true'}
    <div class="control-group">
        <div class="controls span9 offset3">
            <label class="checkbox">
                <input id="widgetSetupRefreshCheckbox" name="refreshActive" type="checkbox"  value="1" />
                Configure refresh time?
            </label>
        </div>
    </div>

    <div class="widgetSetupRefresh hide">
        <div class="control-group control-group-slider">
            <label class="control-label control-label-slider span3">hours</label>
            <div class="controls span9">
                <div id="widgetSetupRefreshHours" class="sliderInput span9" data-index="0" data-min="0" data-max="23"></div>
            </div>
        </div>
        <div class="control-group control-group-slider">
            <label class="control-label control-label-slider span3">minutes</label>
            <div class="controls span9">
                <div id="widgetSetupRefreshMinutes" class="sliderInput span9" data-index="1" data-min="0" data-max="59"></div>
            </div>
        </div>
        <div class="control-group control-group-slider">
            <label class="control-label control-label-slider span3">seconds</label>
            <div class="controls span9">
                <div id="widgetSetupRefreshSeconds" class="sliderInput span9" data-index="2" data-min="0" data-max="59"></div>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label span3">Refresh every</label>
            <div class="controls span9 input-append">
                <input name="refreshValue" class="span2 pagination-right" type="text" value="" />
                <span class="add-on">/ seconds</span>
                <span id="widgetSetupRefreshTime" class="add-on">00:00:00</span>
            </div>
            <span class="controls span9 help-block hide"></span>
        </div>
    </div>

    <script type="text/javascript">
    {literal}
    function getWidgetValidationRulesDefault() {
        return {
            rules: {
                title: {
                   required: true
                },
                column: {
                    required: true
                },
                refreshValue: {
                    digits: true
                }
            }
        }
    }


    function getWidgetDataDefault() {
        var container = jQuery('#widgetSetup');
        var form = container.find('form');
        var output = {
            errors: []
        };

        var elements = {
            title: {
                selector: 'input[name=title]',
                type: 'text'
            },
            column: {
                selector: 'select[name=column]',
                type: 'select'
            },
            refresh: {
                selector: 'input[name=refreshValue]',
                type: 'integer'
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

                case 'integer':
                    value = parseInt(element.val(), 10);

                    if (isNaN(value)) {
                        value = 0;
                    }
                    break;

                case 'select':
                    value = jQuery.trim(element.val());
                    break;
            }

            // Store current value
            output[key] = value;
        });

        return output;
    }

    jQuery(function () {
        var container = jQuery('#widgetSetup');
        var form = container.find('form');
        var columnSelect = form.find('select[name="column"]');
        var refreshContainer = container.find('.widgetSetupRefresh');
        var widgetSetupRefreshTime = refreshContainer.find('#widgetSetupRefreshTime');
        var refreshValueContainer = container.find('input[name=refreshValue]');
        var refreshValue = parseInt(refreshValueContainer.val(), 10);
        var timeBits = [
            parseInt(refreshValue / 3600, 10) % 24,
            parseInt(refreshValue / 60 ) % 60,
            refreshValue % 60
        ];

        timeBits = jQuery.map(timeBits, function(value) {
            return isNaN(value) ? 0 : value;
        });

        var options = {};

        jQuery('.column').each(function() {
            var value = jQuery(this).data('column');

            options[value] = value;
        });

        jQuery.each(options, function(key, value) {
            columnSelect.append(jQuery('<option>', { value : key }).text(value));
        });

        if (refreshValueContainer.val().length > 0) {
            container.find('#widgetSetupRefreshCheckbox').prop('checked', true);

            refreshContainer.toggleClass('hide');

            updateCleanTimeValue();
        }

        updateCleanTimeValue();

        refreshContainer.find('.controls .sliderInput').each(function () {
            var slider = jQuery(this);

            slider.slider({
                range : 'min',
                min   : parseInt(slider.data('min')),
                max   : parseInt(slider.data('max')),
                value : parseInt(timeBits[slider.data('index')]),
                slide : function (event, ui) {
                    timeBits[slider.data('index')] = parseInt(ui.value, 10);

                    refreshValueContainer.val(timeBits[0] * 60 * 60 + timeBits[1] * 60 + timeBits[2]);

                    updateCleanTimeValue();
                }
            });
        });

        container.on('change', '#widgetSetupRefreshCheckbox', function() {
            refreshContainer.toggleClass('hide');

            if (jQuery(this).is(':checked')) {
            } else {
                refreshValueContainer.val('');

                timeBits[0] = timeBits[1] = timeBits[2] = 0;

                refreshContainer.find('.controls .sliderInput').each(function () {
                    var slider = jQuery(this);

                    slider.slider("value", 0);
                });

                updateCleanTimeValue();
            }
        });

        refreshValueContainer.on('keyup', function() {
            var value = parseInt(jQuery(this).val());

            timeBits = [
                parseInt(value / 3600, 10) % 24,
                parseInt(value / 60 ) % 60,
                value % 60
            ];
            console.log(jQuery(this).val());
            timeBits = jQuery.map(timeBits, function(value) {
                return isNaN(value) ? 0 : value;
            });

            refreshContainer.find('.controls .sliderInput').each(function (index) {
                var slider = jQuery(this);

                slider.slider("value", timeBits[index]);
            });
        });

        function updateCleanTimeValue() {
            widgetSetupRefreshTime.html(fillZeroes(timeBits[0]) +":"+ fillZeroes(timeBits[1]) +":"+ fillZeroes(timeBits[2]));
        }
    });

    function fillZeroes(t) {
        t = t + "";

        return (t.length == 1) ? "0" + t : t;
    }
    {/literal}
    </script>
{/if}