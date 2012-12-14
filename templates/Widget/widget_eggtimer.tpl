<div id="widgetEggTimer">
    <form>
        <label>Select time</label>

        <input name="TimerDuration" type="date" />
        <input type="button" value="Käynnistä kello" />
    </form>

    <div id="EggTimer"></div>

    <div style="display: none;">
        <audio id="AlarmSound" preload="auto" controls="controls" loop="loop">
            <source src="{$baseUrl}sounds/alarm.mp3" type="audio/mpeg" />
            <source src="{$baseUrl}sounds/alarm.ogg" type="audio/ogg" />
        </audio>
    </div>
</div>