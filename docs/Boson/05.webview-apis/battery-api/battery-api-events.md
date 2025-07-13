
# Battery API Events

<primary-label ref="events"/>
<show-structure for="chapter" depth="2"/>

The battery will automatically emit the following events (and intentions)
during its lifecycle.

<note>
More information about events can be found in the <a href="events.md">events 
documentation</a>.
</note>

## Charging State Changed Event
<secondary-label ref="event"/>

An `Boson\WebView\Api\Battery\Event\BatteryChargingStateChanged` event fired 
after charging state has been changed.

```php
class BatteryChargingStateChanged<WebView>
{
    public readonly bool $isCharging;
}
```

## Charging Level Changed Event
<secondary-label ref="event"/>

An `Boson\WebView\Api\Battery\Event\BatteryLevelChanged` event fired
after charging level has been changed.

```php
class BatteryLevelChanged<WebView>
{
    public readonly float<0.0, 1.0> $level;
}
```

## Charging Time Changed Event
<secondary-label ref="event"/>

An `Boson\WebView\Api\Battery\Event\BatteryChargingTimeChanged` event fired
after charging time has been changed.

```php
class BatteryChargingTimeChanged<WebView>
{
    public readonly int<0, max> $chargingTime;
}
```

## Discharging Time Changed Event
<secondary-label ref="event"/>

An `Boson\WebView\Api\Battery\Event\BatteryDischargingTimeChanged` event fired
after discharging time has been changed.

```php
class BatteryDischargingTimeChanged<WebView>
{
    public readonly ?int<0, max> $dischargingTime;
}
```