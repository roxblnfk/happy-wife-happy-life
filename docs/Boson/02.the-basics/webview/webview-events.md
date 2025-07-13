
# WebView Events

<primary-label ref="events"/>
<show-structure for="chapter" depth="2"/>

The webview will automatically emit the following events (and intentions)
during its lifecycle.

To subscribe to events, you can use direct access to the
<a href="events.md#event-listener">event listener</a>.

```php
$webview = $app->webview;

$webview->addEventListener(Event::class, function (Event $e): void {
    var_dump($e);
});
```

The webview instance also supports a more convenient and simple way of
registering events using the `on()` method.

```php
$webview->on(function (Event $event): void {
    var_dump($event);
});
```

<note>
More information about events can be found in the <a href="events.md">events 
documentation</a>.
</note>

## Dom Ready Event
<secondary-label ref="event"/>

An `Boson\WebView\Event\WebViewDomReady` event fired after webview DOM has been
loaded and ready to work.

```php
class WebViewDomReady<WebView>
```

## Favicon Changing Intention
<secondary-label ref="linux-limitations"/>
<secondary-label ref="macos-limitations"/>
<secondary-label ref="intention"/>

An `Boson\WebView\Event\WebViewFaviconChanging` intention to change the
window's icon from loaded HTML content.

<tabs>
<tab title="Linux/GTK4">
<warning>
An intention does not change the windows icon.

Icon change intention has no effect.
</warning>
</tab>
<tab title="MacOS/WebKit">
<warning>
Provides no way to access favicons.

Icon change intention has no effect.
</warning>
</tab>
</tabs>

```php
class WebViewFaviconChanging<WebView>
```

<tip>
If intention is cancelled, the window icon has not been changed.
</tip>

## Favicon Changed Event
<secondary-label ref="event"/>

An `Boson\WebView\Event\WebViewFaviconChanged` event fired after the window's
icon has been changed and the `Boson\WebView\Event\WebViewFaviconChanging`
intention has not been cancelled.

```php
class WebViewFaviconChanged<WebView>
```

## Message Received Event
<secondary-label ref="event"/>

An `Boson\WebView\Event\WebViewMessageReceived` intention to
<a href="https://developer.mozilla.org/en-US/docs/Web/API/Window/postMessage">receive message</a> 
from the webview.

```php
class WebViewMessageReceived<WebView> 
{
    public readonly string $message;

    public function ack(): void;
}
```

- `$message` - Received message string value.

<note>
The <code>ack()</code> method marks the message as accepted and processed.
</note>

<tip>
The <code>stopPropagation()</code> method works in a similar way to 
<code>ack()</code>, but is not recommended due semantic conflicts.
</tip>

## Navigating Intention
<secondary-label ref="intention"/>

An `Boson\WebView\Event\WebViewNavigating` intention to change the
webview's URL (navigating to passed URL).

```php
class WebViewNavigating<WebView> 
{
    public readonly string $url;
    public readonly bool $isNewWindow;
    public readonly bool $isRedirection;
    public readonly bool $isUserInitiated;
}
```

- `$url` - The URL address by which navigation occurs.
- `$isNewWindow` - Navigation to the specified URL should have been made
  in a new window.
- `$isRedirection` - Navigation to the specified URL occurs using a redirect.
- `$isUserInitiated` - Navigation to the specified URL does not occur
  automatically, but is initialized by the user.

<tip>
If intention is cancelled, the URL navigation will be cancelled.
</tip>

## Navigated Event
<secondary-label ref="event"/>

An `Boson\WebView\Event\WebViewNavigated` event fired after the webview has been
navigated to the given URL and the `Boson\WebView\Event\WebViewNavigating`
intention has not been cancelled.

```php
class WebViewNavigated<WebView> 
{
    public readonly string $url;
}
```

- `$url` - The URL address by which navigation occurs.

## Title Changing Intention
<secondary-label ref="intention"/>

An `Boson\WebView\Event\WebViewTitleChanging` intention to change the
window title from loaded HTML content.

```php
class WebViewTitleChanging<WebView> 
{
    public readonly string $title;
}
```

- `$title` - Expected title string to be set.

<tip>
If intention is cancelled, then the window title has not been changed.
</tip>

## Title Changed Event
<secondary-label ref="event"/>

An `Boson\WebView\Event\WebViewTitleChanged` event fired after window title has
been changed and the `Boson\WebView\Event\WebViewTitleChanging`
intention has not been cancelled.

```php
class WebViewTitleChanged<WebView> 
{
    public readonly string $title;
}
```

- `$title` - Title string from the HTML content of the webview.