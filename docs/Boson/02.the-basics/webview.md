# WebView

<show-structure for="chapter" depth="2"/>

The `WebView` class represents a webview in the Boson application. It provides 
a way to manage html content, including JavaScript scripts, styles, functions, 
and more.

## Main WebView
<secondary-label ref="read-only"/>

The `Application::$webview` property provides convenient access to the WebView
instance of the <tooltip term="main window">main window</tooltip>.

<tip>
This is a <tooltip term="facade">facade property</tooltip> that internally 
accesses the webview of the default window inside the window manager.
</tip>

```php
$app = new Boson\Application();

// Access the main WebView
$webview = $app->webview;
```

<warning>
Behavior is similar to the <tooltip term="main window">main window</tooltip>:

If you try to access the <code>$webview</code> property after the all windows 
has been closed, a <code>NoDefaultWindowException</code> will be thrown.
</warning>


## URL Navigation

The <code>WebView::$url</code> property allows you to load custom
html content from any address (including real ones from internet).

```php
$webview->url = 'https://bosonphp.com';
```

When setting the URL, webview attempts to load data from the specified source, 
but the address may change based on the behavior of the target page (for example, 
triggering scripts or redirects), so the result displays the real address, and
not the one that was set.

```php
$app = new Boson\Application();

$app->webview->url = 'https://github.com/BosonPHP';

// After set the new URL, the navigation has 
// not yet taken place:
//
// string("URL: about:blank\n")
//
echo 'URL: ' . $app->webview->url . "\n";

$app->on(function (WebViewNavigated $e) use ($app): void {
    // The navigation occurs later, for this you 
    // should subscribe to the required event:
    //
    // string("URL: https://github.com/BosonPHP\n")
    //
    echo 'URL: ' . $app->webview->url . "\n";
});
```

<tip>
WebView navigation also fires a 
<a href="webview-events.md#navigated-event">corresponding event</a> that can be 
subscribed to using the <a href="events.md">event system</a>.
</tip>


## HTML Content
<secondary-label ref="write-only"/>
<secondary-label ref="insecure"/>

The `WebView::$html` property allows you to load custom
html content without navigation to any address.

```php
$webview->html = '<button>Do Not Click Me!</button>';
```

<warning>
Direct HTML loading implemented via <code>data:</code> protocol is an 
<a href="https://developer.mozilla.org/en-US/docs/Web/Security/Secure_Contexts">insecure context</a>
which does NOT allow the implementation of 
<a href="https://developer.mozilla.org/en-US/docs/Web/Security/Secure_Contexts/features_restricted_to_secure_contexts">some functionality</a>.
</warning>

<tip>
WebView navigation also fires a 
<a href="webview-events.md#navigated-event">corresponding event</a> that can be 
subscribed to using the <a href="events.md">event system</a>.
</tip>


## State
<secondary-label ref="read-only"/>

The `WebView::$state` property provides access to the current state 
of the webview.

```php
// Check if the webview is loading
if ($webview->state === Boson\WebView\State::Loading) {
    echo "WebView is currently loading content\n";
}
```

This property will contain one of the possible values of
`Boson\WebView\State` enum.

```php
enum State
{
    /**
     * Navigation to a new URL.
     */
    case Navigating;

    /**
     * Data is being loaded from the specified URL.
     */
    case Loading;

    /**
     * Readiness for work with document.
     */
    case Ready;
}
```
