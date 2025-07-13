# Introduction To The Compiler

<show-structure for="chapter" depth="2"/>

The build process converts your application's source code into an executable 
optimized for the production environment. 

This requires a `boson-php/compiler` component that is supplied separately 
from the `boson-php/runtime`.

<tip>
It is recommended to install the compiler dependency as a <code>--dev</code> 
dependency since it is required exclusively for development.
</tip>

## Installation

<tldr>
    <p>
        Via <a href="https://getcomposer.org/doc/01-basic-usage.md#installing-dependencies">Composer</a>:
    </p>
    <p>
        <code lang="bash">composer require boson-php/compiler --dev</code>
    </p>
</tldr>

**Requirements:**

* `PHP ^8.4`

## Compilation Process

The process of building an application consists of 
several consecutive steps:

1. The first step is to try to read the `boson.json` [configuration file](compiler-configuration.md).
2. Next, all settings explicitly passed to the console command arguments are 
   applied; for example, the `--platform=macos` argument will explicitly 
   override configuration `"platform"` field value.
3. Next, the application is assembled into a [single phar archive](https://www.php.net/manual/en/book.phar.php). 
   The archive is placed in the [output directory](compiler-configuration.md#config-output).
4. Next, the PHP runtime, application code and settings are compiled into a 
   single executable file.
5. The final step is to move the built application and its dependencies to 
   the appropriate [build directory](compiler-configuration.md#config-output).

<code-block lang="mermaid">
    stateDiagram-v2
       IC: Read Configuration
       OC: Applying CLI arguments
       PH: Assembling the PHAR
       CM: Compilation
       INI: Applying ini settings
       BC: Selecting a PHP runtime
       OU: Copying dependencies to the build directory

       [*] --> IC
       IC --> OC
       OC --> PH
       OC --> INI
       OC --> BC
       INI --> CM
       PH --> CM
       BC --> CM
       CM --> OU
       OU --> [*]
</code-block>

<note>
In the simplest case, there will be 2 files left in 
the output directory: 
<list>
<li>
The application executable file (<code>.exe</code>, 
<code>.dmg</code>, etc).
</li>
<li>
A library for working with WebView (<code>.dll</code>, 
<code>.so</code>, <code>.dylib</code>, etc).
</li>
</list>
</note>

## Usage

Compiler package provides a command line tool that can be executed with the 
`php vendor/bin/boson` command. For example, you can run the `list` command to 
get information about the capabilities of the console utility.

```bash
php vendor/bin/boson list
```

Then the output result will be approximately the same as on the screen

```html
Boson Command Line 0.13.4

Usage:
  command [options] [arguments]

Options:
  -h, --help            Display help for the given command. When 
                        no command is given display help for the 
                        list command
      --silent          Do not output any message
  -q, --quiet           Only errors are displayed. All other output 
                        is suppressed
  -V, --version         Display this application version
      --ansi|--no-ansi  Force (or disable --no-ansi) ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for 
                        normal output, 2 for more verbose output 
                        and 3 for debug

Available commands:
  [[[compile|compiler-building.md]]]  Compile application to executable binary
  help     Display help for a command
  [[[init|compiler-configuration.md]]]     Initialize configuration
  [[[list|#usage]]]     List commands
  pack     Pack application files to phar assembly
```
