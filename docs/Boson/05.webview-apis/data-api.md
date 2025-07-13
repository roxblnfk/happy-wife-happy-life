# Data API

<show-structure for="chapter" depth="2"/>

This API should be used to receive arbitrary data from the client.

The API is available in the `WebView::$data` property.

```php
$app = new Boson\Application();

$app->webview->data; // Access to Data API
```


## Synchronous Access

You can directly get data from document using JavaScript code 
that returns specific data to PHP.

```php
$app = new Boson\Application();

$app->on(function (WebViewDomReady $e) use ($app): void {

    var_dump($app->webview->data->get('document.location')); 
    
    // array:10 [
    //   "ancestorOrigins" => []
    //   "href" => "https://nesk.me/"
    //   "origin" => "https://nesk.me"
    //   "protocol" => "https:"
    //   "host" => "nesk.me"
    //   "hostname" => "nesk.me"
    //   "port" => ""
    //   "pathname" => "/"
    //   "search" => ""
    //   "hash" => ""
    // ]
});

$app->webview->url = 'https://nesk.me';
```

<warning>
Please note that the sync request CAN NOT be processed if the
application is not running since at the time of the call the document 
is not yet available.

<code-block lang="php">
$app = new Boson\Application();

var_dump($app->webview->data->get('document.location'));
//
// Boson\WebView\Api\Data\Exception\ApplicationNotRunningException: 
//     Request "document.location" could not be processed
//     because application is not running
//
</code-block>

If the page is currently loading, synchronous requests are also unavailable.

<code-block lang="php">
$app = new Boson\Application();

$app->on(function (Boson\WebView\Event\WebViewNavigating $e): void {
    var_dump($e->subject->data->get('document.location'));
    //
    // Boson\WebView\Api\Data\Exception\WebViewIsNotReadyException:
    //     Request "document.location" could not be processed
    //     because webview is in navigating state
    //
});

$app->webview->url = 'https://example.com';
</code-block>
</warning>

<note>
WebView also provides a more convenient way (facade method <code>get()</code>) 
to get arbitrary data from document.

Just use <code>WebView::get()</code> instead of get method
from <code>WebView::$data</code>.

<p>&nbsp;</p>

<compare>
<code-block lang="php">
$js = 'document.location';

$webview->data->get($js);
</code-block>
<code-block lang="php">
$js = 'document.location';

$webview->get($js);
</code-block>
</compare>

In all examples from here on, the short facade method will
be used to simplify the examples.

</note>

### Synchronous Access Timeout

Note that synchronous access is instant in most cases, but can sometimes 
cause timeout errors and call termination.

For example, if you try to use a deferred call, receiving it synchronously
```php
$app->webview->get('fetch("https://very-slow-client/request.json")');
//
// Boson\WebView\Api\Data\Exception\StalledRequestException: 
//    Request "fetch(\"https://very-slow-client/request.json\")" 
//    is stalled after 0.10s of waiting
//
```

Timeout of 0.1s is the default value, which can be changed 
globally in the settings.

If you are sure that a long query is acceptable, you can set other limits 
on the query explicitly by passing the timeout (in seconds) as the 
second argument.

```php
$result = $app->webview->get('very_long_function()', timeout: \INF);
```

## Async Access

In addition to a direct data retrieval, you can send a deferred 
asynchronous requests. Such a request will return the result 
when it is ready.

Such calls are less convenient, since they use 
[PHP Promise A+](https://promisesaplus.com/), but can be used at any time.

```php
$app = new Boson\Application();

$app->webview->data->defer('document.location')
    ->then(function ($data) {
        var_dump($data);
        
        // array:10 [
        //   "ancestorOrigins" => []
        //   "href" => "data:text/html;base64,"
        //   "origin" => "null"
        //   "protocol" => "data:"
        //   "host" => ""
        //   "hostname" => ""
        //   "port" => ""
        //   "pathname" => "text/html;base64,"
        //   "search" => ""
        //   "hash" => ""
        // ]
    });

$app->webview->html = '';
```