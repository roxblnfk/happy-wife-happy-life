# Dialog API

<show-structure for="chapter" depth="2"/>

The API provides functionality for interacting with files and 
directories through dialog windows.

You can access the subsystem using `Application::$dialog` property.

```php
$app = new Boson\Application();

$app->dialog; // Access to Dialog API
```

## Select File

To select a single file from the file system, use the `selectFile` method. 
The method will return the path to the file as a `string` after it is selected. 
If the user declines the operation during the selection, the method 
will return `null`.

```php
$app = new Boson\Application();

$file = $app->dialog->selectFile();

if ($file !== null) {
    echo 'File: ' . $file;
}
```

The method takes two optional arguments. The first one is responsible for 
the directory in which the dialog window will be opened.

<tip>
If no <code>$directory</code> argument is passed, an attempt will be made to use 
the current working directory.
</tip>

```php
$app = new Boson\Application();

$file = $app->dialog->selectFile(
    directory: '/path/to/directory',
);
```

The second argument contains a list of filters for selecting files.

```php
$app = new Boson\Application();

$image = $app->dialog->selectFile(
    filter: ['*.jpg', '*.png', '*.gif'],
);
```

## Select Multiple Files

If you need to select multiple files, you should use the `selectFiles` method.
The method always returns an `array` of the selected files, and if the process 
is canceled, the array will be **empty**.

```php
$app = new Boson\Application();

foreach ($app->dialog->selectFiles() as $file) {
    echo 'File: ' . $file . "\n";
}
```

Just like with [single file selection](#select-file), this method takes two 
optional arguments with a `$directory` and a `$filter`.

```php
$app = new Boson\Application();

$files = $app->dialog->selectFiles(
    directory: '/expected/directory',
);
```

## Select Directory

To call the dialog window with directory selection, call the `selectDirectory`
method. Unlike [file selection](#select-file), this dialog allows you to select 
only a specific directory.

```php
$app = new Boson\Application();

$directory = $app->dialog->selectDirectory();

if ($directory !== null) {
    echo 'Directory: ' . $directory;
}
```

Just like with [single file selection](#select-file), this method takes two
optional arguments with a `$directory` and a `$filter`.

```php
$app = new Boson\Application();

$directory = $app->dialog->selectDirectory(
    directory: '/expected/directory',
);
```

## Select Multiple Directories

If you need to select multiple directories, you should use the `selectDirectories`
method. The method always returns an `array` of the selected directories, and 
if selection process is canceled, the array will be **empty**.

```php
$app = new Boson\Application();

foreach ($app->dialog->selectDirectories() as $directory) {
    echo 'Directory: ' . $directory . "\n";
}
```

Just like with [single file selection](#select-file), this method takes two
optional arguments with a `$directory` and a `$filter`.

```php
$app = new Boson\Application();

$directories = $app->dialog->selectDirectories(
    directory: '/expected/directory',
);
```

## Open File

You can open any address (URI or file) using a registered program. For example, 
when trying to open `https://bosonphp.com`, most likely (depending on your OS 
settings) a browser with the specified address will be opened, and when trying 
to open `example.txt`, a text editor.

```php
$app = new Boson\Application();

// Open the file with an image viewer
$app->dialog->open('/path/to/image.jpg');

// Open the URI with a browser
$app->dialog->open('http://example.com');
```