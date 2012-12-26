<div id="widgetEggTimer">
    <div class="countdown">
        <div>
            <div class="counter-wrapper time">
                <ul>
                    <li>
                        <p class="hours">00</p>
                    </li>
                    <li>:</li>
                    <li>
                        <p class="minutes">00</p>
                    </li>
                    <li>:</li>
                    <li>
                        <p class="seconds">00</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <form class="form-horizontal">
        <div class="setup">
            <div class="control-group">
                <label class="control-label">hours</label>
                <div class="controls">
                    <div id="eggTimerValueHours" class="sliderInput" data-index="0" data-min="0" data-max="23"></div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">minutes</label>
                <div class="controls">
                    <div id="eggTimerValueMinutes" class="sliderInput" data-index="1" data-min="0" data-max="59"></div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">seconds</label>
                <div class="controls">
                    <div id="eggTimerValueSeconds" class="sliderInput" data-index="2" data-min="0" data-max="59"></div>
                </div>
            </div>
        </div>
        <div class="control-group">
            <button class="btn" role="reset" type="button" disabled="disabled">Reset</button>
            <button class="btn" role="pause" type="button" disabled="disabled">Pause</button>
            <button class="btn" role="stop" type="button" disabled="disabled">Stop</button>
            <button class="btn" role="start" type="button">Start</button>
            <button class="btn" role="setup" type="button">Setup</button>
        </div>
    </form>

    <div style="display: none;">
        <audio class="alarmSound" preload="auto" controls="controls" loop="loop">
            <source src="{$pageBaseHref}sounds/alarm.mp3" type="audio/mpeg" />
            <source src="{$pageBaseHref}sounds/alarm.ogg" type="audio/ogg" />
        </audio>
    </div>
</div>

<script type="text/javascript">
    jQuery(function () {
        var container = jQuery('#widgetEggTimer');
        var counter = container.find('.countdown');
        var alarm = container.find('.alarmSound')[0];
        var timeBits = [];

        container.find('.counter-wrapper ul li p').each(function(index) {
            timeBits[index] = parseInt(jQuery(this).html(), 10);
        });

        if (timeBits[0] == 0 && timeBits[1] == 0 && timeBits[2] == 0) {
            container.find('button[role=start]').attr('disabled', 'disabled');
        } else {
            container.find('button[role=start]').removeAttr('disabled');
        }

        function resetAlarm() {
            alarm.pause();
        }

        container.find('.setup .controls .sliderInput').each(function () {
            var slider = jQuery(this);

            slider.slider({
                range : 'min',
                min   : parseInt(slider.data('min')),
                max   : parseInt(slider.data('max')),
                value : parseInt(timeBits[slider.data('index')]),
                slide : function (event, ui) {
                    if (counter.data('started') == true) {
                        counter.jCounter('stop');
                        counter.data('started', false);

                        container.find('.counter-wrapper ul li p').each(function(index) {
                            timeBits[index] = parseInt(jQuery(this).data('value'), 10);

                            jQuery(this).text(fillZeroes(timeBits[index]));
                        });
                    }

                    timeBits[slider.data('index')] = parseInt(ui.value, 10);

                    if (timeBits[0] == 0 && timeBits[1] == 0 && timeBits[2] == 0) {
                        container.find('button[role=start]').attr('disabled', 'disabled');
                    } else {
                        container.find('button[role=start]').removeAttr('disabled');
                    }

                    container.find('.counter-wrapper ul li p').each(function(index) {
                        jQuery(this).text(fillZeroes(timeBits[index]));
                        jQuery(this).data('value', timeBits[index]);
                    });
                }
            });
        });

        container.find('button[role=setup]').on('click', function () {
            container.find('button[role=reset]').attr('disabled', 'disabled');
            container.find('button[role=stop]').attr('disabled', 'disabled');
            container.find('button[role=pause]').attr('disabled', 'disabled');

            resetAlarm();

            if (counter.data('started') == true) {
                counter.jCounter('stop');

                container.find('.counter-wrapper ul li p').each(function(index) {
                    jQuery(this).text(fillZeroes(timeBits[index]));
                });
            }

            if (timeBits[0] == 0 && timeBits[1] == 0 && timeBits[2] == 0) {
                container.find('button[role=start]').attr('disabled', 'disabled');
            } else {
                container.find('button[role=start]').removeAttr('disabled');
            }

            container.find('.setup').toggle();
        });

        container.find('button[role=start]').on('click', function() {
            container.find('.setup').hide();

            if (counter.data('started') == true) {
                counter.data('started', true);
            } else {
                var duration = parseInt(timeBits[0], 10) * 60 * 60 + parseInt(timeBits[1], 10) * 60 + parseInt(timeBits[2], 10);

                counter.data('started', true);

                resetAlarm();

                counter.jCounter({
                    format: "hh:mm:ss",
                    twoDigits: 'on',
                    customDuration: duration,
                    callback: function() {
                        container.find('button[role=pause]').attr('disabled', 'disabled');

                        alarm.play();
                    }
                });
            }

            counter.jCounter('start');

            container.find('button[role=reset]').removeAttr('disabled');
            container.find('button[role=stop]').removeAttr('disabled');
            container.find('button[role=pause]').removeAttr('disabled');
            container.find('button[role=start]').attr('disabled', 'disabled');
        });

        container.find('button[role=stop]').on('click', function() {
            counter.jCounter('stop');

            resetAlarm();

            container.find('button[role=reset]').attr('disabled', 'disabled');
            container.find('button[role=stop]').attr('disabled', 'disabled');
            container.find('button[role=pause]').attr('disabled', 'disabled');
            container.find('button[role=start]').attr('disabled', 'disabled');
        });

        container.find('button[role=pause]').on('click', function() {
            counter.jCounter('pause');

            resetAlarm();

            container.find('button[role=start]').removeAttr('disabled');
            container.find('button[role=pause]').attr('disabled', 'disabled');
        });

        container.find('button[role=reset]').on('click', function() {
            counter.jCounter('reset');

            resetAlarm();

            container.find('button[role=reset]').removeAttr('disabled');
            container.find('button[role=stop]').removeAttr('disabled');
            container.find('button[role=pause]').removeAttr('disabled');
            container.find('button[role=start]').attr('disabled', 'disabled');
        });
    });

    function fillZeroes(t) {
        t = t + "";

        return (t.length == 1) ? "0" + t : t;
    }
</script>