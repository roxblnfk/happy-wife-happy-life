# Battery API

<show-structure for="chapter" depth="2"/>
<secondary-label ref="security-limitations"/>
<secondary-label ref="macos-limitations"/>

This API provides information about 
[device battery](https://developer.mozilla.org/en-US/docs/Web/API/Battery_Status_API).

<warning>
MacOS/WebKit does not support this API. In the future, it 
may be implemented using native syscalls.
</warning>

The API is available in the `WebView::$battery` property.

```php
$app = new Boson\Application();

$app->webview->battery; // Access to Battery API
```

## Battery Level
<secondary-label ref="read-only"/>

To get the current battery charge level you can use the read-only 
`$level` property. The battery level contain a float value 
between `0.0` and `1.0`.

```php
$level = $app->webview->battery->level;

echo 'Charge level is ' . (int) ($level * 100) . '%';
// 
// Expects: Charge level is 100%
// 
```

<note>
For non-mobile devices the charge level is always <code>1.0</code>
</note>

## Charging Status
<secondary-label ref="read-only"/>

To get the battery charging status you can use the read-only
`$isCharging` property.

```php
$isCharging = $app->webview->battery->isCharging;

echo 'The battery is ' . ($isCharging ? '' : 'not ') . 'charging now';
// 
// Expects: The battery is charging now
// 
```

<note>
For non-mobile devices the charging status is always <code>true</code>
</note>

## Charging Time
<secondary-label ref="read-only"/>

To get the time until the battery is fully charged, use the read-only
`$chargingTime` property. The charging time property will contain an 
integer value in seconds.

```php
$chargingTime = $app->webview->battery->chargingTime;

echo vsprintf('It takes another %d seconds to fully charge', [
    $chargingTime,
]);
```

<note>
For non-mobile devices the charging time is always <code>0</code>
</note>

## Discharging Time
<secondary-label ref="read-only"/>

To get the time until the battery is fully discharged, use the read-only
`$dischargingTime` property. The discharging time property will 
contain an integer value in seconds or `null` in case the 
discharge time is not available.

```php
$dischargingTime = $app->webview->battery->dischargingTime;

if ($dischargingTime === null) {
    echo 'Battery is missing';
    
    return;
}

echo vsprintf('It takes another %d seconds to fully discharge', [
    $dischargingTime,
]);
```

<note>
For non-mobile devices the discharging time is always <code>null</code>
</note>