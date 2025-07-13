# PSR HTTP Adapter

The PSR HTTP Bridge provides the ability to convert internal requests and
responses of the [Schema API](schemes-api.md) to those compatible with 
any PSR-7 compatible frameworks (e.g. [Yii3](https://github.com/yiisoft/demo),
[Spiral](https://spiral.dev/), [Slim](https://www.slimframework.com/), etc.).

<note>
This bridge is NOT included by default in the <code>boson-php/runtime</code> 
and must be installed separately.
</note>


## Installation

<tldr>
    <p>
        Via <a href="https://getcomposer.org/doc/01-basic-usage.md#installing-dependencies">Composer</a>:
    </p>
    <p>
        <code lang="bash">composer require boson-php/psr-http-bridge</code>
    </p>
</tldr>

**Requirements:**

* `PHP ^8.4`
* `psr/http-message ^1.0|^2.0`
* `psr/http-factory ^1.0`

## Usage

To work with any PSR-7/17-compatible framework, you are provided with a 
corresponding adapter `Boson\Bridge\Psr\Http\Psr7HttpAdapter`.

```php
use Boson\Application;
use Boson\ApplicationCreateInfo;
use Boson\Bridge\Psr\Http\Psr7HttpAdapter;
use Boson\WebView\Api\Schemes\Event\SchemeRequestReceived;

// Create an application
$app = new Application(new ApplicationCreateInfo(
    schemes: [ 'psr7' ],
));

// Create PSR-7 HTTP adapter
$psr7 = new Psr7HttpAdapter(
    requests: new YourVendorPsr17ServerRequestFactory(),
);

// Subscribe to receive a request
$app->on(function (SchemeRequestReceived $e) use ($psr7): void {
    $psr7Request = $psr7->createRequest($e->request);
    
    // ...do something, like:
    // 
    // $psr7Response = $app->handle($psr7Request);
    //
    
    $e->response = $psr7->createResponse($psr7Response);
});

$app->webview->url = 'psr7://app/example';
```
