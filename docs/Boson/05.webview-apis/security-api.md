# Security API

<show-structure for="chapter" depth="2"/>

This API provides information about 
[security context](https://developer.mozilla.org/en-US/docs/Web/Security/Secure_Contexts) 
used to access [other APIs](https://developer.mozilla.org/en-US/docs/Web/Security/Secure_Contexts/features_restricted_to_secure_contexts).

The API is available in the `WebView::$security` property.

```php
$app = new Boson\Application();

$app->webview->security; // Access to Security API
```

<note>
Your context will most likely be <b>secure</b> if you don't use the 
<code>data:</code> or <code>about:</code> protocol schemes
</note>

## Current Context
<secondary-label ref="read-only"/>

To get the current security status you can use the read-only 
`$isSecureContext` property.

```php
$isSecure = $app->webview->security->isSecureContext;

echo 'Context is ' . ($isSecure ? 'secure' : 'insecure');
// 
// Expects: Context is secure
//
```