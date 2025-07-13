
# WebView Configuration

<primary-label ref="configuration"/>
<show-structure for="chapter" depth="2"/>

The webview configuration class `Boson\WebView\WebViewCreateInfo` is
<tooltip term="optional class">optional</tooltip> and serves as a convenient way to 
define default settings for webview of main window instance.


## Default User Agent
<secondary-label ref="config-only"/>

Sets a custom user agent string for the WebView.

```php
$webviewConfig = new Boson\WebView\WebViewCreateInfo( 
    userAgent: 'MyCustomUserAgent/1.0',
);
```


## Storage (Persistent Client Settings)
<secondary-label ref="config-only"/>

Configures persistent storage settings for the WebView.

If storage is enabled, persistent settings will be saved between
different app executions, such as saved passwords, history, and other data
shared across browsers.

```php
$webviewConfig = new Boson\WebView\WebViewCreateInfo( 
    storage: '/path/to/storage', // Defaults to false (disabled)
);
```

The storage value can take on different types:
- `false` - The storage will be **disabled**.
- `null` - The storage will be **enabled** and directory will be
  determined automatically based on the current working directory.
- `string` - The storage will be **enabled** and directory will be
  based on the passed argument.


## Extra Flags
<secondary-label ref="config-only"/>

Sets additional WebView configuration flags.

```php
$webviewConfig = new Boson\WebView\WebViewCreateInfo( 
    flags: [
        'enable-javascript' => false,
    ],
);
```

These are additional <b>platform-dependent</b> launch flags and their behavior may
differ on different platforms.

<tabs>
<tab title="Windows">
    For WebView2 list of all available flags can be 
    <a href="https://learn.microsoft.com/en-us/dotnet/api/microsoft.web.webview2.core.corewebview2environmentoptions.additionalbrowserarguments">found on MSDN</a>
    and <a href="https://www.chromium.org/developers/how-tos/run-chromium-with-flags/">chromium.org</a>
    <code-block lang="PHP">
    $webviewConfig = new Boson\WebView\WebViewCreateInfo(flags: [
        '--disable-features' => ['feature1', 'feature2'],
        '--do-something',
    ]);
    </code-block>
</tab>
<tab title="Linux (GTK)">
    For WebkitGTK list of all available flags can be 
    <a href="https://webkitgtk.org/reference/webkitgtk/stable/class.Settings.html#properties">found at webkitgtk.org</a>
    <code-block lang="PHP">
    $webviewConfig = new Boson\WebView\WebViewCreateInfo(flags: [
        'enable-javascript' => false,
    ]);
    </code-block>
</tab>
<tab title="MacOS">
    For WKWebView list of all available flags can be 
    <a href="https://developer.apple.com/documentation/webkit/wkwebviewconfiguration">found at developer.apple.com</a>
    <code-block lang="PHP">
    $webviewConfig = new Boson\WebView\WebViewCreateInfo(flags: [
         'upgradeKnownHostsToHTTPS' => true,
         'defaultWebpagePreferences.allowsContentJavaScript' => false,
         'preferences.minimumFontSize' => 10,
         'applicationNameForUserAgent' => 'Boson',
    ]);
    </code-block>
</tab>
</tabs>


## Context Menu
<secondary-label ref="config-only"/>

Controls whether the default context menu (right mouse button) is enabled.

```php
$webviewConfig = new Boson\WebView\WebViewCreateInfo( 
    contextMenu: true, // Default is false
);
```


## Dev Tools
<secondary-label ref="config-only"/>

Enables or disables developer tools for the WebView.

```php
$webviewConfig = new Boson\WebView\WebViewCreateInfo( 
    devTools: true, // Default is null
);
```

The developer tools settings can take one of the following values:
- `true` - Enables developer tools window.
- `false` - Disables developer tools window.
- `null` - Depends on the application `debug` settings.
  Developer tools will be enabled if debug is enabled and vice versa.
