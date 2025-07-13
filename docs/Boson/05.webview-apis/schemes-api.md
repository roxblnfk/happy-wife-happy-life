# Schemes API

<show-structure for="chapter" depth="2"/>

You can register custom scheme/protocols and intercept standard one.

This API allows you to intercept all calls to addresses according to 
registered schemes and send custom responses generated entirely 
programmatically, without actual requests to the server (without network).

## Registration
<secondary-label ref="macos-limitations"/>
<secondary-label ref="linux-limitations"/>

To enable processing of specific protocols, you should specify
them in the list of schemes.

```php
$app = new Boson\Application(new Boson\ApplicationCreateInfo(
    schemes: [ 'test' ],
));

$app->webview->url = 'test://hello.world/';
```

<tabs>
<tab title="MacOS/WebKit">
<warning>
Does NOT support interception of some existing schemes:
<code>http</code>, <code>https</code>, <code>ws</code>, <code>wss</code>, 
<code>ftp</code>, <code>file</code>, <code>data</code>.

You will get an error similar to the following:
<code-block>
*** Terminating app due to uncaught 
    exception 'NSInvalidArgumentException', reason: "'https' is a URL 
    scheme that WKWebView handles natively"
*** First throw call stack:
    ...
</code-block>
</warning>
</tab>
<tab title="Linux/GTK4">
<warning>
Does NOT support interception of some existing schemes:
<code>http</code>, <code>https</code>, <code>ws</code>, <code>wss</code>, 
<code>ftp</code>, <code>file</code>, <code>data</code>.

You will get an error similar to the following:
<code-block>
** (process:3226): WARNING **: 16:12:59.122: 
Registering special URI scheme https is no longer allowed
</code-block>
</warning>
</tab>
</tabs>

## Requests Interception

After enabling the interception of all the necessary protocols (in this
case, `test`), you can start catching the corresponding events of sending
requests to this protocol (to this scheme).

```php
use Boson\WebView\Api\Schemes\Event\SchemeRequestReceived;

$app = new Boson\Application(new Boson\ApplicationCreateInfo(
    // List of intecpted schemes
    schemes: [ 'test' ],
));

$app->on(function (SchemeRequestReceived $e): void {
    echo sprintf("%s %s\r\n", $e->request->method, $e->request->url);
    
    foreach ($e->request->headers as $header => $value) {
        echo sprintf("%s: %s\r\n", $header, $value);
    }
    
    echo sprintf("\r\n\r\n%s", $e->request->body);
    
    //
    // Result may looks like:
    //
    // GET test://hello.world/
    // accept: text/html,application/xhtml+xml,application/xml;q=0.9,etc...
    // upgrade-insecure-requests: 1
    // user-agent: Mozilla/5.0 etc...
    // sec-ch-ua: "Microsoft Edge WebView2";v="135", etc...
    // sec-ch-ua-mobile: ?0
    // sec-ch-ua-platform: "Windows"
    //
});

$app->webview->url = 'test://hello.world/';
```

In that case, if you need to block a request to a specified URL,
you can cancel it.

```php
$app->on(function (SchemeRequestReceived $e): void {
    $e->cancel();
});
```

In addition to canceling a request, you can also simulate a
response from a resource.

```php
$app->on(function (SchemeRequestReceived $e): void {
    $e->response = new Boson\Http\Response(
        body: 'Hello World!',
    );
});
```

Or a more complex response example:

```php
$app->on(function (SchemeRequestReceived $e): void {
    $e->response = new Boson\Http\Response(
        body: json_encode(['error' => 'Something went wrong']),
        headers: ['content-type' => 'application/json'],
        status: 404,
    );
});
```

Or using specific `JsonResponse` response instance:

```php
$app->on(function (SchemeRequestReceived $e): void {
    $e->response = new Boson\Http\JsonResponse(
        body: ['error' => 'Something went wrong'],
        status: 404,
    );
});
```