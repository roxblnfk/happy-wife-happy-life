# Application

<show-structure for="chapter" depth="2"/>

The `Boson\Application` is the central 
component of the Boson and is responsible for managing the application 
lifecycle. It provides a single entry point for creating and managing web 
applications using WebView.

The application is responsible for:

- Lifecycle management ([startup](application.md#creating),
  [shutdown](application.md#stopping), etc).
- [Window](window.md) creation and management.
- [WebView](webview.md) integration for web content display.
- [Application](application-events.md), [WebView](webview-events.md) and 
  [Window](webview-events.md) event handling.

...and more

Architecturally, the application, like most key components, is divided into 
two "layers":
- The main functionality belonging to the application itself.
- Facades over methods and properties of descendants for quick access to the 
  main internal components of the core.

## Creating

To create an application, simply create a new `Boson\Application` object. 
This will be sufficient for the vast majority of cases.

```php
$app = new Boson\Application();
```

The application constructor also contains several optional arguments that you 
can pass explicitly if you wish.

The first optional argument is responsible for the `Boson\ApplicationCreateInfo` 
application settings and allows you to fine-tune the application's operation.

<tip>
More details about the application configuration are written on the 
<a href="application-configuration.md">corresponding documentation pages</a>.
</tip>

```php
$config = new Boson\ApplicationCreateInfo(
    // application configuration options
);

$app = new Boson\Application(info: $config);
```

The remaining optional parameters are responsible for passing external 
dependencies. 

For example, the second argument takes an optional reference to an external 
`Psr\EventDispatcher\EventDispatcherInterface` event dispatcher 
to which all events within the application can be delegated.

```php
$dispatcher = new Any\Vendor\PsrEventDispatcher();

$app = new Boson\Application(dispatcher: $dispatcher);
```

After creating the application, you will have access to the API to work with 
it, and after the necessary actions, the application will automatically start, 
<a href="application-configuration.md#autorun">unless otherwise specified</a>.

## Launching
<secondary-label ref="blocking"/>

The application can be started manually using the <code>run()</code> method. 

<code-block lang="PHP">
$app = new Boson\Application();
$app->run();
</code-block>

<warning>
The <code>run()</code> method is <b>blocking</b>, which means it will block 
the current execution thread until the application is stopped.

<code-block lang="PHP">
$app = new Boson\Application();

echo 'Application will start...';

$app->run(); // This is a blocking operation

echo 'Application WAS stopped'; // The code will be executed ONLY 
                                // after stopping an application
</code-block>
</warning>


## Stopping

The application can be stopped at any time using the `quit()` method:

<code-block lang="PHP">
$app->quit();
</code-block>

<tip>
For correct organization of the code, the stop should be made from the 
event subscription
<code-block lang="PHP">
$app = new Boson\Application();

$app->on(function (SomeEvent $e) use ($app): void {
    $app->quit();
});

$app->run();
</code-block>
</tip>

To find out if the application is running, you can use the 
`Application::$isRunning` property, which returns `true` if the application 
is currently running.

<code-block lang="PHP">
$app = new Boson\Application();

// any code

if ($app->isRunning === false) {
    $app->run();
}
</code-block>


## Identifier
<secondary-label ref="read-only"/>

The `Boson\ApplicationId` is a unique identifier for each application
instance. The identifier is needed to compare different applications
for their equivalence.

To get application identifier use the `Application::$id` property.

```php
$app = new Boson\Application();

echo 'ID: ' . $app->id;
```

An identifier is a value object and contains methods
for comparison and conversion to scalars.

<code-block lang="PHP">
if ($app1->id->equals($app2->id)) {
    echo sprintf('The %s app is equals to %s app', $app1, $app2);
}
</code-block>

