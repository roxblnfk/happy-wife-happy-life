# Application Events

<primary-label ref="events"/>
<show-structure for="chapter" depth="2"/>

The application will automatically emit the following events (and intentions)
during its lifecycle.

To subscribe to events, you can use direct access to the
<a href="events.md#event-listener">event listener</a>.

```php
$app->addEventListener(Event::class, function (Event $e) {
    var_dump($e);
});
```

The application instance also supports a more convenient and simple way of
registering events using the `on()` method.

```php
$app->on(function (Event $event): void {
    var_dump($event);
});
```

<note>
More information about events can be found in the <a href="events.md">events 
documentation</a>.
</note>


## Starting Intention
<secondary-label ref="intention"/>

An `Boson\Event\ApplicationStarting` intention to start the application.

```php
class ApplicationStarting<Application>
```

<tip>
If it is cancelled, the application will not be launched.
</tip>

## Started Event
<secondary-label ref="event"/>

An `Boson\Event\ApplicationStarted` event fired after the application has been
launched and the `Boson\Event\ApplicationStarting` intention has not been
cancelled.

```php
class ApplicationStarted<Application>
```

## Stopping Intention
<secondary-label ref="intention"/>

An `Boson\Event\ApplicationStopping` intention to stop the application.

```php
class ApplicationStopping<Application>
```

<tip>
If it is cancelled, the application will not be stopped.
</tip>

## Stopped Event
<secondary-label ref="event"/>

An `Boson\Event\ApplicationStopped` event fired after the application has been
stopped and the `Boson\Event\ApplicationStopping` intention has not been
cancelled.

```php
class ApplicationStopped<Application>
```