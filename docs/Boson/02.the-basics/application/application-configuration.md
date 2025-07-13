
# Application Configuration

<primary-label ref="configuration"/>
<show-structure for="chapter" depth="2"/>

The application configuration class `Boson\ApplicationCreateInfo` is
<tooltip term="optional class">optional</tooltip> and serves as a convenient 
way to define default settings for initializing your app.


## Application Name
<secondary-label ref="config-only"/>

The name of the application.

```php
$appConfig = new Boson\ApplicationCreateInfo( 
    name: 'Example Application',
);
```

The value is optional and can be used for user needs, as well as for internal ones.
<tip>
For example as a <a href="https://learn.microsoft.com/en-us/windows/win32/learnwin32/creating-a-window#window-classes">
    WindowClass (WNDCLASS.lpszClassName)
</a> identifier on Windows OS.
</tip>


## Intercepted Schemes
<secondary-label ref="config-only"/>

Defines custom schemes that your application can handle.
These schemes allow you to create custom protocols for your application.

```php
$appConfig = new Boson\ApplicationCreateInfo( 
    schemes: [ 'boson' ], // Default is empty array
);
```

More detailed information about the schemes is available 
on the [Schemes API](schemes-api.md) page.

<tip>
Each registered scheme in this list will produce a 
<a href="schemes-api-events.md#request-intention">SchemeRequestReceived</a> intention 
when attempting to access a resource located at an address with this protocol.
</tip>


## Threads Count
<secondary-label ref="config-only"/>

Specifies the number of physical threads for the application. This affects how
many concurrent operations your application can handle.

```php
$appConfig = new Boson\ApplicationCreateInfo( 
    threads: 4, // Default is null
);
```

<note>
If the value is not specified (defined as <code>null</code>), the number of 
threads will correspond to the number of cores in the CPU.
</note>


## Debug Mode
<secondary-label ref="config-only"/>

Enables or disables debug features, like dev tools and logging. When enabled,
provides additional diagnostic information and developer tools.

```php
$appConfig = new Boson\ApplicationCreateInfo( 
    debug: true, // Default is null
);
```

<note>
If the value is not specified, the debug mode will be set according to the 
current <code>php.ini</code> settings (depends on whether you are using the 
development <code>php.ini</code> settings)
</note>

<tip>
The debug mode settings also affects the default settings of child 
configurations, such as <a href="webview-configuration.md#dev-tools">developer 
tools</a> (if they are not set explicitly).
</tip>


## Application Library
<secondary-label ref="config-only"/>

Specifies the path to a custom 
[frontend library](https://github.com/boson-php/frontend-src/releases) that should 
be loaded with the application.

```php
$appConfig = new Boson\ApplicationCreateInfo( 
    library: __DIR__ . '/path/to/custom/library.dll', // Default is null
);
```

<note>
In most cases this is not required and the library will be selected 
automatically based on the current operating system and CPU architecture.
</note>


## Quit On Close
<secondary-label ref="config-only"/>

Determines whether the application should terminate when all windows are closed.
If set to `false`, the application will continue running in the background.

```php
$appConfig = new Boson\ApplicationCreateInfo( 
    quitOnClose: true, // Default is true
);
```


## Autorun
<secondary-label ref="config-only"/>

Responsible for automatic application launch. If autorun is set to
`false`, you will need to launch the application yourself at the
moment when it is needed.

```php
$appConfig = new Boson\ApplicationCreateInfo( 
    autorun: false, // Default is true
);
```

<warning>
Autorun will NOT work if the application has already been launched manually.
</warning>

<warning>
Autorun will NOT work if any serious errors (errors or exceptions) 
occurred before launching.
</warning>
