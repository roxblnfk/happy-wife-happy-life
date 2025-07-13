# Events

<show-structure for="chapter" depth="2"/>

Boson provides several different events. Event subscriptions are hierarchical 
and are arranged in the following order.

```mermaid
classDiagram
    class EventDispatcherInterface["Psr\EventDispatcher\EventDispatcherInterface"]
    EventDispatcherInterface <.. Application : delegates events to optional PSR dispatcher
    class Application["Boson\Application"]{
        <<implements EventListenerInterface>>
        +addEventListener(string $event, callable $listener) CancellableSubscriptionInterface
        +removeEventListener(SubscriptionInterface $subscription) void
        +removeListenersForEvent(object|string $event) void
        +getListenersForEvent(object|string $event) iterable
    }
    Application <.. WindowManager : delegates events to application
    class WindowManager["Boson\Window\Manager\WindowManager"]{
        <<implements EventListenerInterface>>
        +addEventListener(string $event, callable $listener) CancellableSubscriptionInterface
        +removeEventListener(SubscriptionInterface $subscription) void
        +removeListenersForEvent(object|string $event) void
        +getListenersForEvent(object|string $event) iterable
    }
    WindowManager <.. Window : delegates events to windows list
    class Window["Boson\Window\Window"]{
        <<implements EventListenerInterface>>
        +addEventListener(string $event, callable $listener) CancellableSubscriptionInterface
        +removeEventListener(SubscriptionInterface $subscription) void
        +removeListenersForEvent(object|string $event) void
        +getListenersForEvent(object|string $event) iterable
    }
    Window <.. WebView : delegates events to window
    class WebView["Boson\Window\WebView"]{
        <<implements EventListenerInterface>>
        +addEventListener(string $event, callable $listener) CancellableSubscriptionInterface
        +removeEventListener(SubscriptionInterface $subscription) void
        +removeListenersForEvent(object|string $event) void
        +getListenersForEvent(object|string $event) iterable
    }
```

You can subscribe to a specific event at any level, receiving all the 
corresponding events of the context.

```php
// Subscribe to events of application and all its windows
$app->addEventListener(Event::class, fn($e) => ...);

// Subscribe to events of one specific window
$window->addEventListener(Event::class, fn($e) => ...);
```

You can read more about each type of event in the corresponding
sections of the documentation.

- [Application](application-events.md)
- [Window](window-events.md)
- [WebView](webview-events.md)

## Events and Intentions

Any event is a fact. Events **can not** be changed or rejected. 

Unlike events, an intent is an attempt by the application or any of its 
components to do something. Any intent **can** be rejected or modified.

Both events and intentions use a common event bus within 
the Boson application.

### Stop Propagation

<secondary-label ref="event"/>
<secondary-label ref="intention"/>

To stop the "event bubbling" there is a method `Event::stopPropagation()`.

When this method is called, the event will not be delegated to the upper level 
of event listener and all subsequent subscribers of this level of event listener
that were registered after.

```php
// ❌ event will NOT fired
$app->addEventListener(Event::class, fn($e) => ...);

// ✅ event will be fired (registered BEFORE the propagation stop)
$app->window->addEventListener(Event::class, fn($e) => ...);

// ✅ event will be fired (callback stops "event bubbling")
$app->window->addEventListener(Event::class, function ($e) {
    $e->stopPropagation();
});

// ❌ event will NOT fired (registered AFTER the propagation stop)
$app->window->addEventListener(Event::class, fn($e) => ...);
```

The read-only property `Event::$isPropagationStopped` (or alternative 
PSR-compatible method `Event::isPropagationStopped()`) is available to check 
for propagation stop events.

```php
if ($event->isPropagationStopped) {
    echo 'Event propagation is stopped';
}
```

### Timestamp

<secondary-label ref="event"/>
<secondary-label ref="intention"/>

Each event also contains the timestamp `Event::$time` read-only property 
of the event creation. The property contain an int32 value of UNIX timestamp.

```php
echo date('d-m-Y', $event->time); // string("06-05-2025")
```

### Cancellation

<secondary-label ref="intention"/>

Method `Intention::cancel()` is used to stop and prevent the standard behavior.

For example, if you call the `cancel()` method when trying to stop an 
application, the application will not be stopped. Therefore, the application 
stop event will not be called either.

```php
$app->addEventListener(ApplicationStopping::class, function ($e) {
    $e->cancel();
});
```

## Listener Provider

Each core class such as [Application](application.md),
[Window](window.md) and [WebView](webview.md) exposes a 
method `EventListenerProviderInterface::on()` that 
provides a simpler and more convenient way to subscribe to events.

### Simple Listen Events

To subscribe to events, it is enough to call the `on()` method, which must 
contain a callback with one parameter, which contains the event type.

For example, to subscribe to the window creation event, you can write 
the following code.

```php
use Boson\Application;
use Boson\Window\Event\WindowCreated;

$app = new Application();

$app->on(function (WindowCreated $e): void {
    echo 'Window ' . $e->subject->id . ' has been created';
});
```

If you don't need to process the event object, you can use an alternative 
option by passing the event name as the first parameter and the callback 
as the second.

```php
use Boson\Application;
use Boson\Window\Event\WindowCreated;

$app = new Application();

$app->on(WindowCreated::class, function (): void {
    echo 'Window has been created';
});
```

<tip>
The second callback parameter also allows the event object to be present 
as the first argument.

<code-block lang="php">
$app->on(Event::class, function (Event $e) { ... });
</code-block>
</tip>


## Event Listener

The `Boson\Contracts\EventListener\EventListenerInterface` provides a way to 
subscribe to and handle events in the Boson application. It allows you to 
register callbacks that will be executed when specific events occur.

### Listen Events

To subscribe to events, you need to use the 
`EventListenerInterface::addEventListener()` method:

```php
$app->addEventListener(Event::class, function (Event $event): void {
    // Handle the event
});
```

The first parameter is the event class you want to listen to, and the second 
parameter is a callback function that will be executed when the event occurs.

### Cancel Subscription

Each `EventListenerInterface::addEventListener()` returns the 
`SubscriptionInterface` object. To cancel a subscription, 
use the `cancel()` method.

```php
$subscription = $app->addEventListener($event, $callback);

// Cancel subscription
$subscription->cancel();
```

<tip>
The <code>EventListenerProviderInterface::on()</code> method also returns 
a subscription object.

<code-block lang="PHP">
$subscription = $app->on(function (Event $e) { ... });

// Cancel subscription
$subscription->cancel();
</code-block>
</tip>

### Cancel All Subscriptions

To cancel all existing event subscriptions of a certain type, 
call the `removeListenersForEvent()` method.

```php
// Cancel all EventName subscriptions
$app->removeListenersForEvent(EventName::class);
```