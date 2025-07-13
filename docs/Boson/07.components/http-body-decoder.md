# HTTP Body Decoder

<show-structure for="chapter" depth="2"/>

Provides the ability to decode the request body and obtain useful information 
from there based on the request information.

<warning>
This component is not included by default in the <code>boson-php/runtime</code> 
and must be installed separately.
</warning>


## Installation

<tldr>
    <p>
        Via <a href="https://getcomposer.org/doc/01-basic-usage.md#installing-dependencies">Composer</a>:
    </p>
    <p>
        <code lang="bash">composer require boson-php/http-body-decoder</code>
    </p>
</tldr>

**Requirements:**

* `PHP ^8.4`


## Usage

The component provides a flexible system for decoding HTTP request bodies with
support for different content types.

### Basic Usage

The main entry point is the `BodyDecoderFactory` class, which automatically 
selects the appropriate decoder based on the request:

```php
use Boson\Component\Http\Body\BodyDecoderFactory;
use Boson\Component\Http\Body\FormUrlEncodedDecoder;
use Boson\Component\Http\Body\MultipartFormDataDecoder;
use Boson\Component\Http\Request;

// Create decoders factory
$factory = new BodyDecoderFactory([
    // Decodes "application/x-www-form-urlencoded" requests
    new FormUrlEncodedDecoder(),
    // Decodes "multipart/form-data" requests
    new MultipartFormDataDecoder(),
]);

// Decode request
$decodedBody = $factory->decode( $bosonRequest );
```

### Supported Decoders

#### Form URL Encoded

The `FormUrlEncodedDecoder` handles `application/x-www-form-urlencoded` 
content type:

```php
use Boson\Component\Http\Body\FormUrlEncodedDecoder;
use Boson\Component\Http\Request;

$decoder = new FormUrlEncodedDecoder();

$request = new Request(
    method: 'POST',
    headers: ['Content-Type' => 'application/x-www-form-urlencoded'],
    body: 'name=John&age=30'
);

$decodedBody = $decoder->decode(new Request(
    method: 'POST',
    headers: ['Content-Type' => 'application/x-www-form-urlencoded'],
    body: 'name=John&age=30'
));

//
// Expected output:
//
// array:2 [
//   "name" => "John"
//   "age" => "30"
// ]
//
```

#### Multipart Form Data

The `MultipartFormDataDecoder` handles `multipart/form-data` content type, 
including file uploads:

```php
use Boson\Component\Http\Body\MultipartFormDataDecoder;
use Boson\Component\Http\Request;

$decoder = new MultipartFormDataDecoder();

$decodedBody = $decoder->decode(new Request(
    method: 'POST',
    headers: [
        'Content-Type' => 'multipart/form-data;' 
            . ' boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW'
    ],
    // Note: The HTTP protocol uses \r\n delimiters
    body: str_replace(["\r\n", "\n"], "\r\n", <<<'BODY'
        ------WebKitFormBoundary7MA4YWxkTrZu0gW
        Content-Disposition: form-data; name="name"
        
        John
        ------WebKitFormBoundary7MA4YWxkTrZu0gW
        Content-Disposition: form-data; name="file"; filename="test.txt"
        Content-Type: text/plain
        
        Hello World
        ------WebKitFormBoundary7MA4YWxkTrZu0gW--
        BODY),
));

// 
// Expected output:
//
// array:2 [
//   "name" => "John"
//   "file" => "Hello World"
// ]
//
```

### Creating Custom Decoders

You can create custom decoders by implementing 
the `SpecializedBodyDecoderInterface`:

<note>
The <code>SpecializedBodyDecoderInterface</code> interface means that the 
decoder will respond to a specific specialized request type 
(see method <code>isDecodable()</code>).
</note>

```php
use Boson\Component\Http\Body\SpecializedBodyDecoderInterface;
use Boson\Component\Http\Request;

class JsonBodyDecoder implements SpecializedBodyDecoderInterface
{
    public function decode(RequestInterface $request): array
    {
        return (array) (\json_decode($request->body, true) ?? []);
    }

    public function isDecodable(RequestInterface $request): bool
    {
        return $request->headers->first('content-type')
            === 'application/json';
    }
}

$factory = new BodyDecoderFactory([
    new JsonBodyDecoder(),
    // ... other decoders
]);
```

### Error Handling

The factory handles decoder errors gracefully:

```php
use Boson\Component\Http\Body\BodyDecoderFactory;
use Boson\Component\Http\Request;

$factory = new BodyDecoderFactory($decoders);

// If no decoder is suitable or decoding fails
$decodedBody = $factory->decode(new Request(
    method: 'POST',
    headers: ['Content-Type' => 'unknown/type'],
    body: 'invalid data'
));

// 
// Expected output:
//
// [] (empty array)
//
```