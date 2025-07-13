# Web Components API

<primary-label ref="experimental"/>
<show-structure for="chapter" depth="2"/>

You can create your own custom web components (html elements) processed by PHP.

The API is available in the `WebView::$components` property.

```php
$app = new Boson\Application();

$app->webview->components; // Access to Web Components API
```


## Creation

For creating your own component, you should use the `add()` method, passing 
there the tag name and a reference to the existing component class.

<note>
The tag name for a custom component must contain a dash (`-`) character.
</note>

```php
class MyExampleComponent {}

$app = new Boson\Application();

// Tag component name
$tag = 'my-element';

// Component class name
$component = MyExampleComponent::class; 

$app->webview->components->add($tag, $component);

$app->webview->html = '<my-element>Example</my-element>';
```

For more convenient component management, you can use inheritance from the 
`Boson\WebView\Api\WebComponents\WebComponent` class.

```php
class MyExampleComponent extends WebComponent
{
    // do something
}
```

## Template Rendering

By default, the component does not contain any HTML content (it uses the 
default body one passed to it). If you want to decorate it somehow or define 
custom content, you should add the 
`Boson\WebView\Api\WebComponents\Component\HasTemplateInterface` interface and 
implement `render()` method.

```php
class MyExampleComponent implements HasTemplateInterface
{
    public function render(): string
    {
        return '<b>This is SPARTAAAAAAAAAAaaaAAAA!!!</b>';
    }
}
```

<img src="web-component-content.png" alt="Web Component Template" />

## Shadow DOM

In order to switch to shadow house rendering mode, you should implement 
the `Boson\WebView\Api\WebComponents\Component\HasShadowDomInterface` interface.

The shadow DOM is similar to the regular renderer, but isolates its behavior 
from global styles and supports slots.

To include the content of an element inside a rendered template, the `<slot />` 
tag should be used.

<note>
See more information about templates and slots in 
<a href="https://developer.mozilla.org/en-US/docs/Web/API/Web_components/Using_templates_and_slots">MDN Documentation</a>
</note>

```php
class MyExampleComponent implements HasShadowDomInterface
{
    public function render(): string
    {
        return '<b>This is <slot></slot>!!!</b>';
    }
}
```

<warning>
Slot tags (<code>&lt;slot /&gt;</code>) only work in Shadow DOM
</warning>

<warning>
Using the short <code>&lt;slot /&gt;</code> version instead of 
full <code>&lt;slot&gt;&lt;/slot&gt;</code> may not work correctly
</warning>

If you try to render the contents of a `<slot />` without a Shadow DOM (using 
`HasTemplateInterface`), no data will be received.

<img src="web-component-content-slot.png" alt="Web Component Content Slot" />

When you turn on the Shadow DOM (using `HasShadowDomInterface`), the contents 
will be passed to the `<slot />`.

<img src="web-component-shadow-dom-slot.png" alt="Web Component Shadow DOM Slot" />

## Lifecycle Callbacks

Creating a new PHP component instance means physically creating the object, 
including through JavaScript.

```js
const component = document.createElement('my-element');
//
// At this point, a PHP component instance will be created.
// That is, the MyExampleComponent::__construct() will be called.
//
```

In order to accurately determine that an element is connected to any physical 
node of the DOM document, the 
`Boson\WebView\Api\WebComponents\Component\HasLifecycleCallbacksInterface` 
interface should be implemented.

```php
class MyExampleComponent implements HasLifecycleCallbacksInterface
{
    public function onConnect(): void
    {
        var_dump('Component is connected to document');
    }

    public function onDisconnect(): void
    {
        var_dump('Component is disconnected from document');
    }
}
```

## Properties

Each web component supports the ability to create properties and manage them.

```js
let el = document.createElement('my-element');

el.exampleProperty = 42;
```

By default, this property does not affect the operation of PHP code in any way.
However, if you need to track the state of properties, you can use the 
corresponding `Boson\WebView\Api\WebComponents\Component\HasPropertiesInterface` 
interface.

```php
class MyExampleComponent implements HasPropertiesInterface
{
    public function onPropertyChanged(string $property, mixed $value): void
    {
        // ...
    }

    public static function getPropertyNames(): array
    {
        // ...
    }
}
```

In particular, if you need to receive information about changes in property 
`exampleProperty`, then you should add it to the `getPropertyNames()` list.

```php
public static function getPropertyNames(): array
{
    return [ 'exampleProperty' ];
}
```

<warning>
<b>Properties</b> and <a href="#attributes"><b>attributes</b></a> are different 
things. Properties are located directly on the object and can contain arbitrary 
data, while an attribute can be specified in HTML tags and can contain 
exclusively string values.
</warning>

## Methods

Each web component supports the ability to create methods and process them.

```html
<my-element onclick="this.update()">Example</my-element>
```

If you leave the code as is, then when you click on the element, 
a JS error will be thrown: `Uncaught TypeError: this.update is not a function`.

To implement a method (for example, "`update()`"), you should implement the 
`Boson\WebView\Api\WebComponents\Component\HasMethodsInterface` interface.

```php
class MyExampleComponent implements HasMethodsInterface
{
    public function onMethodCalled(string $method, array $args = []): mixed
    {
        // ...
    }

    public static function getMethodNames(): array
    {
        // ...
    }
}
```

If you want to add support for method `update()`, then `getMethodNames()` method
must return the corresponding name.

```php
public static function getMethodNames(): array
{
    return [ 'update' ];
}
```

After this, when you click on the element, the `onMethodCalled` method will 
be called with the `$method` argument equal to the name of the called method 
`"update"` and empty arguments.

```php
class MyExampleComponent implements HasMethodsInterface
{
    public function onMethodCalled(string $method, array $args = []): mixed
    {
        if ($method !== 'update') {
            throw new \BadMethodCallException('Invalid method ' . $method);
        }

        var_dump($method . ' has been invoked with passed arguments');
        // update has been invoked with passed arguments
        var_dump($args);
        // array(0) {}

        return null;
    }

    public static function getMethodNames(): array
    {
        return [ 'update' ];
    }
}
```

Don't be afraid to return exceptions from methods, 
they can be handled correctly.

```php
public function onMethodCalled(string $method, array $args = []): mixed
{
    throw new \BadMethodCallException('Invalid method ' . $method);
}
```

After calling method `update()` you will get the following JS error:
```
Uncaught (in promise) Error: 
    BadMethodCallException: Invalid method update in .../test.php 
    on line 12
at <anonymous>:1:61
```

You can also call these methods from the JS directly.

```js
const component = document.createElement('my-element');

try {
    let result = await component.update();
} catch(e) {
    // Catch PHP Exception
}
```

## Attributes

In addition to methods, each HTML element has attributes. You can subscribe 
to change, add or remove an attribute by implementing the 
`Boson\WebView\Api\WebComponents\Component\HasAttributesInterface` interface.

```php
class MyExampleComponent implements HasAttributesInterface
{
    public function onAttributeChanged(
        string $attribute, 
        ?string $value, 
        ?string $previous,
    ): void {
        // ...
    }

    public static function getAttributeNames(): array
    {
        // ...
    }
}
```

Method `getAttributeNames()` must return a list of attributes
(strings) to be processed.

Method `onAttributeChanged()` contains a callback that is called when 
the attribute value changes.

| `$value` | `$previous` | Meaning                           |
|----------|-------------|-----------------------------------|
| `string` | `null`      | Attribute has been added          |
| `string` | `string`    | Attribute value has been changed  |
| `null`   | `string`    | Attribute has been removed        |

## Events

Each web component supports the ability to listen several component events 
and process them.

To implement an event listener (for example, "[`click`](https://developer.mozilla.org/en-US/docs/Web/API/PointerEvent)"), 
you should implement the `Boson\WebView\Api\WebComponents\Component\HasEventListenersInterface` interface.

```php
class MyExampleComponent implements HasEventListenersInterface
{
    public function onEventFired(string $event, array $args = []): void
    {
        // ...
    }

    public static function getEventListeners(): array
    {
        // ...
    }
}
```

If you want to listen `click` event, then `getEventListeners()` method
must return the corresponding name.

```php
public static function getEventListeners(): array
{
    return [ 
        'click' => [],
    ];
}
```

If you require any specific information related to an event, then the list 
of event fields should be passed as an array value.

For example, `click` event contain readonly properties such as `clientX` 
and `clientY`.

```php
public static function getEventListeners(): array
{
    return [ 
        'click' => [ 
            // "clientX" property from "click" event
            'clientX',
            // "clientY" property from "click" event
            'clientY',
        ], 
    ];
}
```

If specific PHP array key names are required for these properties,
they should be specified as keys.

```php
public static function getEventListeners(): array
{
    return [ 
        'click' => [ 
            // "clientX" property from "click" event passed as "x"
            'x' => 'clientX',
            // "clientX" property from "click" event passed as "y"
            'y' => 'clientY',
        ], 
    ];
}
```

## Reactive Context

The reactive context allows you to modify and retrieve values from a 
component programmatically.

By default, it is passed to the constructor as the first argument.

```php
use Boson\WebView\Api\WebComponents\ReactiveContext;
use Boson\WebView\WebView;

class MyExampleComponent
{
    public function __construct(
        private ReactiveContext $ctx,
    ) {}
}
```

The context contains methods for working with attributes.

```php
class MyExampleComponent implements HasMethodsInterface
{
    public function __construct(
        private ReactiveContext $ctx,
    ) {}

    public function update(): void
    {
        // Add attribute `some="any"` in case of attribute is not defined
        if (!$this->ctx->attributes->has('some')) {
            $this->ctx->attributes->set('some', 'any');
        }
    }
    
    /// Delegate "onMethodCalled()" call to the update() method...
}
```

To work with content, you can use the `$content` property.

```php
class MyExampleComponent implements HasMethodsInterface
{
    public function __construct(
        private ReactiveContext $ctx,
    ) {}

    public function update(): void
    {
        if (!$this->ctx->content->html === '') {
            $this->ctx->content->html = '<b>Hello World!</b>';
        }
    }
    
    /// Delegate "onMethodCalled()" call to the update() method...
}
```