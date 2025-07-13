# HTTP Requests and Responses

<show-structure for="chapter" depth="2"/>

The component provides a set of HTTP structures that provide information 
about requests, responses, and their dependencies.

<note>
This component already included in the <code>boson-php/runtime</code>, 
so no separate installation is required when using the runtime.
</note>


## Installation

<tldr>
    <p>
        Via <a href="https://getcomposer.org/doc/01-basic-usage.md#installing-dependencies">Composer</a>:
    </p>
    <p>
        <code lang="bash">composer require boson-php/http</code>
    </p>
</tldr>

**Requirements:**

* `PHP ^8.4`

## Usage

The component provides request and response implementations.

### Request

The `Request` class represents an immutable HTTP request:

```php
use Boson\Component\Http\Request;

// Create a new request with default values
$request = new Request();

// Create a custom request
$request = new Request(
    method: 'POST',
    url: 'https://example.com/api/users',
    headers: [
        'content-type' => 'application/json',
        'authorization' => 'Bearer token123'
    ],
    body: '{"name": "John", "age": 30}'
);
```

All properties are immutable and can only be accessed, 
not modified.

<tip>
All request objects are created by the Boson itself within events, such as 
<a href="schemes-api-events.md">SchemeRequestReceived</a>. Therefore, to 
ensure that the object within an event or intention will <b>NOT be changed</b> 
and all listeners receive identical information, the request 
object <b>is immutable</b>.
</tip>

### Response

The `Response` class represents a mutable HTTP response:

```php
use Boson\Component\Http\Response;

// Create a new response with default values
$response = new Response();

// Create a custom response
$response = new Response(
    body: '<h1>Hello World</h1>',
    headers: [
        'content-type' => 'text/html',
        'x-custom-header' => 'value'
    ],
    status: 200
);

// Modify response
$response->headers->add('x-new-header', 'new value');
$response->body = 'New content';
$response->status = 201;
```

<tip>
All responses are created by developer in any form. Therefore, for convenience,
they are made <b>mutable</b> by default.
</tip>

### JSON Response

The `JsonResponse` class extends `Response` to provide JSON-specific 
functionality:

```php
use Boson\Component\Http\JsonResponse;

// Create a JSON response with default values
$response = new JsonResponse();

// Create a custom JSON response
$response = new JsonResponse(
    data: ['name' => 'John', 'age' => 30],
    headers: ['x-custom-header' => 'value'],
    status: 200
);

// Create a JSON response with custom encoding flags
$response = new JsonResponse(
    data: ['name' => 'John', 'age' => 30],
    jsonEncodingFlags: JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
);
```


### Headers

Both request and response use the `HeadersMap` class for header management. 
To get a list of headers, use the `headers` property in HTTP objects 
(for example, `$request->headers` or `$response->headers`).

The request contains an immutable `HeadersMap` object which provides methods 
for reading header information.

```php
use Boson\Component\Http\HeadersMap;

// Check if header exists
if ($request->headers->has('content-type')) {
    // Get header value
    $contentType = $request->headers->first('content-type');
}
```

While the response contains a mutable implementation of the list 
of `MutableHeadersMap` headers which represents not only methods of 
obtaining information, but also its modifications.

```php
use Boson\Component\Http\HeadersMap;

// Add new header
if (!$response->headers->has('x-custom-header')) {
    $response->headers->add('x-custom-header', 'value');
}

// Remove header
$response->headers->remove('content-type');
```

<note>The headers map is <b>case-insensitive</b> (lowercased) for header names</note>