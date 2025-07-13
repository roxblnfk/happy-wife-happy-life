
# Window Events

<primary-label ref="events"/>
<show-structure for="chapter" depth="2"/>

The window will automatically emit the following events (and intentions)
during its lifecycle.

To subscribe to events, you can use direct access to the
<a href="events.md#event-listener">event listener</a>.

```php
$window = $app->window;

$window->addEventListener(Event::class, function (Event $e) {
    var_dump($e);
});
```

The window instance also supports a more convenient and simple way of
registering events using the `on()` method.

```php
$window->on(function (Event $event): void {
    var_dump($event);
});
```

<note>
More information about events can be found in the <a href="events.md">events 
documentation</a>.
</note>

## Closing Intention
<secondary-label ref="intention"/>

An `Boson\Window\Event\WindowClosing` intention to close the window.

<tip>
If it is cancelled, the window will not be closed.
</tip>

```php
class WindowClosing<Window>
```

## Closed Event
<secondary-label ref="event"/>

An `Boson\Window\Event\WindowClosed` event fired after the window has been
closed and the `Boson\Window\Event\WindowClosing` intention has not been
cancelled.

```php
class WindowClosed<Window>
```

## Created Event
<secondary-label ref="event"/>

An `Boson\Window\Event\WindowCreated` event fired after window has been created.

```php
class WindowCreated<Window>
```

## Decorated Event
<secondary-label ref="event"/>

An `Boson\Window\Event\WindowDecorated` event fired after
<a href="window.md#decorations">window controls</a> visibility changed.

```php
class WindowDecorated<Window> 
{
    public readonly bool $isDecorated;
}
```

- `$isDecorated` - Visibility status of the OS window controls.

<note>
The event differs from a 
<a href="window-events.md#decoration-changed-event">decoration changed</a> in 
that it reacts exclusively to the turning on or off of window controls 
(minimize, maximize, restore, close buttons and title bar) visibility.
</note>

## Decoration Changed Event
<secondary-label ref="event"/>

An `Boson\Window\Event\WindowDecorationChanged` event fired after
<a href="window.md#decorations">window decoration</a> has been changed.

```php
class WindowDecorationChanged<Window> 
{
    public readonly Boson\Window\WindowDecoration $decoration;
    public readonly Boson\Window\WindowDecoration $previous;
}
```

- `$decoration` - Decorations type of the window.
- `$previous` - Previous decorations type of the window.

## Destroyed Event
<secondary-label ref="event"/>

An `Boson\Window\Event\WindowDestroyed` event fired after window has 
been destroyed (all references to it in the GC have been removed).

```php
class WindowDestroyed<Window>
```


## Focused Event
<secondary-label ref="event"/>

An `Boson\Window\Event\WindowFocused` event fired after
<a href="window.md#focus">window focus</a> has been changed.

```php
class WindowFocused<Window> 
{
    public readonly bool $isFocused;
}
```


- `$isFocused` - Window <a href="window.md#focus">focus status</a>.

<note>
The event is fired not only when window has been focused (in which case the 
<code>$isFocused</code> property will contain <code>true</code>), but also 
when window focus has been lost (in which case the <code>$isFocused</code> 
property will contain <code>false</code>).
</note>

## Maximized Event
<secondary-label ref="event"/>

An `Boson\Window\Event\WindowMaximized` event fired after
<a href="window.md#maximize">window maximized</a> state has been changed.

```php
class WindowMaximized<Window> 
{
    public readonly bool $isMaximized;
}
```

- `$isMaximized` - Window <a href="window.md#maximize">maximized status</a>.

<note>
The event is fired not only when maximizing (in which case the 
<code>$isMaximized</code> property will contain <code>true</code>), but also 
when restoring from maximization (in which case the <code>$isMaximized</code> 
property will contain <code>false</code>).
</note>

## Minimized Event
<secondary-label ref="event"/>

An `Boson\Window\Event\WindowMinimized` event fired after
<a href="window.md#minimize">window minimized</a> state has been changed.

```php
class WindowMinimized<Window> 
{
    public readonly bool $isMinimized;
}
```

- `$isMinimized` - Window <a href="window.md#minimize">minimized status</a>.

<note>
The event is fired not only when minimizing (in which case the 
<code>$isMinimized</code> property will contain <code>true</code>), but also when 
restoring from minimization (in which case the <code>$isMinimized</code> 
property will contain <code>false</code>).
</note>

## Resized Event
<secondary-label ref="event"/>

An `Boson\Window\Event\WindowResized` event fired after
<a href="window.md#size">window size</a> has been changed.

```php
class WindowResized<Window> 
{
    public readonly int $width;
    public readonly int $height;
}
```

- `$width` - Window width dimension in pixels.
- `$height` - Window height dimension in pixels.

<tip>
Window width and height is a non-negative <code>int32</code> (an integer value 
between 0 and 2147483647).
</tip>

## State Changed Event
<secondary-label ref="event"/>

An `Boson\Window\Event\WindowStateChanged` event fired after
<a href="window.md#state">window state</a> has been changed.

```php
class WindowStateChanged<Window> 
{
    public readonly Boson\Window\WindowState $state;
    public readonly Boson\Window\WindowState $previous;
}
```

- `$state` - State type of the window.
- `$previous` - Previous state type of the window.
