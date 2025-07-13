# Scripts API

<show-structure for="chapter" depth="2"/>

This API should be used to register and call arbitrary 
JavaScript code in WebView.

The API is available in the `WebView::$scripts` property.

```php
$app = new Boson\Application();

$app->webview->scripts; // Access to Scripts API
```


## JavaScript Evaluation

You can execute arbitrary code directly on current WebView document.

The code will be executed immediately once on the current page (on the
currently loaded document).

```php
$app = new Boson\Application();

// Load empty page
$app->webview->html = '';

// Evaluate JS code on this page
$app->webview->scripts->eval(
    'document.write("Hello World!")'
);
```

<note>
WebView also provides a more convenient way (facade method <code>eval()</code>) 
to execute arbitrary JavaScript code.

Just use <code>WebView::eval()</code> instead of eval method
from <code>WebView::$scripts</code>.

<p>&nbsp;</p>

<compare>
<code-block lang="php">
$js = 'alert("Hello!")';

$webview->scripts->eval($js);
</code-block>
<code-block lang="php">
$js = 'alert("Hello!")';

$webview->eval($js);
</code-block>
</compare>

In all examples from here on, the short facade method will 
be used to simplify the examples.

</note>


## Ready-state Registration 

You can register a JavaScript code that will be applied to any page.

<note>
This code will be called every time on every page after the document has been
processed by the client and its DOM is ready and available.
</note>

```php
$app = new Boson\Application();

$app->webview->scripts->add(<<<'JS'
    alert('hello');
    JS);
```

Or set scripts from configuration

```php
$app = new Boson\Application(
    window: new Boson\Window\WindowCreateInfo(
        webview: new Boson\WebView\WebViewCreateInfo(
            scripts: [
                "alert('hello')",
            ],
        ),
    ),
);
```

## Preloading

It is worth noting that adding code is available in several options.

In particular, if you need to register a set of scripts that will be executed 
every time on each page before its content is loaded and processed, then you 
should use the `preload()` method.

```php
$app = new Boson\Application();

// "This code will be executed BEFORE the page loads: undefined"
$app->webview->scripts->preload(<<<'JS'
    console.log('This code will be executed BEFORE the page loads: ' 
        + document?.body?.innerHTML);
    JS);
    
// Loading content (making a navigation)
$app->webview->html = '<b>hello</b>';
```