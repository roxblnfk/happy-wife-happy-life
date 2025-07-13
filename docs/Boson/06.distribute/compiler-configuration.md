# Compiler Configuration

<show-structure for="chapter" depth="2"/>

To create a build configuration, use the `init` command. The command will 
create the `boson.json` file in the root of the application with build settings.
It is not required for compilation, but allows better control over all stages 
of the build.

```json5
{
    //
    // The name of your application.
    //
    [[["name": "app",|#config-name]]]
  
    // 
    // List of build architectures.
    //
    [[["arch": [ "amd64", "aarch64" ],|#config-arch]]]
  
    //
    // List of build platforms.
    //
    [[["platform": [ "windows", "linux", "macos" ],|#config-platform]]]
  
    //
    // An application entrypoint PHP file.
    //
    [[["entrypoint": "index.php",|#config-entrypoint]]]

    //
    // An output build directory.
    //
    [[["output": "./build",|#config-output]]]

    //
    // List of rules for including files inside the assembly.
    //
    [[["build": {|#build-config]]]
        "finder": [
            {
                "directory": "vendor",
                "name": "*.php"
            },
            {
                "directory": "vendor/boson-php/runtime/resources/dist",
                "name": "*.js"
            }
        ]
    },
   
    //
    // Additional options for the PHP interpreter
    //
    [[["ini": {|#ini-config]]]
        "memory_limit": "128M"
    }
}
```


## `name` {id="config-name"}

The name of your application.

It is used to create output executable file. For example, if you specify 
`"name": "example"`, the `example.exe` application will be created for 
the Windows platform (and `example` binaries for others).

```json5
{
    "name": "application name",
    // ...
}
```

<note>
If the field is not specified, the <code>"app"</code> name will be used.
</note>


## `arch` {id="config-arch"}

List of build architectures.

You can explicitly specify the CPU architectures your application will be built 
for.

**Available options:**
 - `amd64` (or `x86_64`)
 - `aarch64` (or `arm64`)

```json5
{
    "arch": [ "amd64", "aarch64" ],
    // ...
}
```

<note>
If the field is not specified (including empty array), all available 
architectures will be used.
</note>


## `platform` {id="config-platform"}

List of build platforms.

You can explicitly specify a list of operating systems for which your 
application will be compiled.

**Available options:**
 - `windows` (or `win`/`win32`/`win64`)
 - `linux`
 - `macos` (or `darwin`)

```json5
{
    "platform": [ "windows", "linux", "macos" ],
    // ...
}
```

<note>
If the field is not specified (including empty array), all available 
platforms will be used.
</note>


## `entrypoint` {id="config-entrypoint"}

An application entrypoint PHP file.

In the entrypoint field, you should specify the **relative** path to the file
that will be executed when the application is launched.

```json5
{
    "entrypoint": "path/to/entrypoint.php",
    // ...
}
```

<tip>
The entrypoint will be located on the same path inside the build as outside, 
so you don't have to worry about paths (like 
<code>require __DIR__ . '/vendor/autoload.php';</code>) breaks after building.
</tip>

<note>
If the field is not specified, the <code>"index.php"</code> will be used.
</note>


## `output` {id="config-output"}

An output build directory.

The **relative** path is specified in which all assembly files and the
result of the assembly itself will be placed.

```json5
{
    "build": "./var/build",
    // ...
}
```

<tip>
The build result will be located in this directory depending on the
specified platforms and architectures.

<code-block>
~/&lt;build-directory>/&lt;platform>/&lt;arch>/...
</code-block>

For example, for Windows x64 with the specified build 
directory <code>"./var/build"</code> and <code>"app"</code> 
application name:

<code-block>
~/var/build/windows/amd64/app.exe
~/var/build/windows/amd64/libboson-windows-x86_64.dll
~/var/build/windows/amd64/...etc
</code-block>
</tip>

<note>
If the field is not specified, the <code>"build"</code> directory will be used.
</note>


## `build` {id="build-config"}

List of rules for including files inside the assembly.

This field contains an object with a set of rules. 
Available fields of the object:

- `"files"` - List of files to include.
- `"directories"` - List of directories to include.
- `"finder"` - List of rules (filters) to include.

### `build.files` {id="build-files-config"}

The `"files"` section specifies a list (array) of individual files 
to include in the assembly.

```json5
{
    "build": {
        "files": [
            "./path/to/file.php",
            "./some/awesome.jpg",
            // ...
        ],
        // ...
    },
    // ...
}
```

<tip>
An entrypoint file is automatically included in this list, 
it is not necessary to specify it separately.
</tip>


### `build.directories` {id="build-directories-config"}

The `"directories"` section specifies a list (array) of directories
to include in the assembly.

```json5
{
    "build": {
        "directories": [
            "./public",
            "./resources",
            // ...
        ],
        // ...
    },
    // ...
}
```

<note>
Specifying a directory includes <b>all</b> files, including 
temporary ones or those in <code>.gitignore</code>.
</note>


### `build.finder` {id="build-finder-config"}

The `"finder"` section specifies a list (array) of 
[finder-like](https://symfony.com/doc/current/components/finder.html) rules 
to include in the assembly.

```json5
{
    "build": {
        "finder": [
            {
                // "string" or ["string"]
                "directory": "vendor",

                // "string" or ["string"]
                "not-directory": "vendor/phpunit",

                // "string" or ["string"]
                "name": "*.php",

                // "string" or ["string"]
                "not-name": "Test.php"
            },
            // ...
        ],
        // ...
    },
    // ...
}
```

The `"finder"` may contain an array of objects with, `"name"`, `"not-name"`, 
`"directory"` and `"not-directory"` fields.

<procedure title="name field format">
Filters files by name. All files matching the 
specified rule will be included in the build.
<tip>
The <code>name</code> field may be defined as <code>string</code> or 
<code>array</code> of strings
</tip>
<step>
You may specify a mask where an asterisk means any 
occurrence of any number of characters
<code-block>*.php</code-block>
<code-block>index*</code-block>
</step>
<step>
You can specify a regular expression to check the file name. Such 
an expression must start and end with the <code>/</code> characters.
<code-block>/\.php$/</code-block>
<code-block>/^index.*/</code-block>
</step>
<warning>
The field only checks the file <b>name</b>, not the file path.
</warning>
</procedure>

<note>
To exclude from the selection by names, use the <code>"not-name"</code> field 
with the same capabilities as <code>"name"</code> field.
</note>

<procedure title="directory field format">
Specifies the directory in which to search for files to include.
<tip>
The <code>directory</code> field may be defined as <code>string</code> or 
<code>array</code> of strings
</tip>
<step>
You may define real path to directory.
<code-block>
./path/to/directory
</code-block>
</step>
<step>
You may use <code>*</code> as a wildcard character to search in the 
directories matching a pattern (each pattern has to resolve to at 
least one directory path).
<code-block>
./path/to/*/*/dir
</code-block>
</step>
</procedure>

<note>
To exclude directories, use the <code>"not-directory"</code> field 
with the same capabilities as <code>"directory"</code> field.
</note>

## `ini` {id="ini-config"}

Additional options for the PHP interpreter.

You can specify additional options for the interpreter using 
the settings object.

The key should be one of 
[the available directive](https://www.php.net/manual/en/ini.list.php) names. 
The value may be any scalar (`int`, `float`, `string` or `bool`) value.

```json5
{
    "ini": {
        "memory_limit": "128M",
        "opcache.jit_buffer_size": "32M"
    }
}
```

<tip>
You may use environment variables inside PHP configuration values.
<code-block lang="json5">
{
    "ini": {
        "memory_limit": "${BOSON_MEMORY_LIMIT}"
    }
}
</code-block>

In addition, default values are available for environment variables:
<code-block lang="json5">
{
    "ini": {
        "memory_limit": "${BOSON_MEMORY_LIMIT:-128M}"
    }
}
</code-block>
</tip>