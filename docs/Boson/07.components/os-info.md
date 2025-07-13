# Operating System Info

<show-structure for="chapter" depth="2"/>

The OS Info component provides a robust and flexible way to detect and work with
operating system information in your applications. It offers a comprehensive 
set of features for identifying operating systems, their families, and 
supported standards.

<note>
This component already included in the <code>boson-php/runtime</code>, 
so no separate installation is required when using the runtime.
</note>


## Installation

<tldr>
    <p>
        Via <a href="https://getcomposer.org/doc/01-basic-usage.md#installing-dependencies">Composer</a>:
    </p>
    <p>
        <code lang="bash">composer require boson-php/os-info</code>
    </p>
</tldr>

**Requirements:**

* `PHP ^8.4`
* `ext-ffi` (optional, provides more detailed and accurate information about the OS)
* `ext-com_dotnet` (optional, provides more detailed and accurate information about the OS)


## Usage

### Basic Detection

<secondary-label ref="macos-limitations"/>

```php
use Boson\Component\OsInfo\OperatingSystem;

// Get current operating system information
$os = OperatingSystem::createFromGlobals();

// Access basic information
echo 'Family: ' . $os->family . "\n";
echo 'Name: ' . $os->name . "\n";
echo 'Version: ' . $os->version . "\n";
echo 'Codename: ' . ($os->codename ?? '~') . "\n";
echo 'Edition: ' . ($os->edition ?? '~') . "\n";
echo 'Standards: ' . implode(', ', $os->standards) . "\n";
```

This code will output something like the following information

<tabs>
    <tab title="Windows">
        <code-block>
        Family: Windows
        Name: Windows 10 Pro
        Version: 10.0.19045
        Codename: 22H2
        Edition: Professional
        Standards: ~ 
        </code-block>
    </tab>
    <tab title="Linux">
        <code-block>
        Family: Linux
        Name: Ubuntu
        Version: 20.04.6
        Codename: Focal Fossa
        Edition: ~
        Standards: POSIX
        </code-block>
    </tab>
    <tab title="macOS">
        <code-block>
        Family: Darwin
        Name: Darwin
        Version: 24.4.0
        Codename: ~
        Edition: ~
        Standards: POSIX
        </code-block>
        <warning>
        Please note that the information in macOS may not be accurate due 
        to virtualization and testing issues.
        Full implementation of macOS support is possible in the future.
        </warning>
    </tab>
</tabs>

### OS Families

You can get the OS family information from the OS information 
object (`$os->family`). However, if you do not need all the OS information, 
it is enough to get the family separately using the `Family::createFromGlobals()`
method.

```php
use Boson\Component\OsInfo\Family;

// Get current OS family
$family = Family::createFromGlobals();

// Strict compliance
if ($family === Family::BSD) {
    // Only BSD OS
}

// Compatibility check
if ($family->is(Family::BSD)) {
    // BSD and BSD-like, for example:
    //  - BSD
    //  - Solaris
    //  - Darwin (macOS)
    //  - etc
}
```

Please note that the `$family->is()` check includes the check of the family 
itself and its parents.

```php
if ($family->is(Family::Unix)) {
    // All operating systems from the Unix family 
    // will be subject to this check:
    // - Darwin (macOS)
    // - Linux
    // - BSD
    // - ...and other Unix-like
}
```

### Standards Support

```php
use Boson\Component\OsInfo\OperatingSystem;
use Boson\Component\OsInfo\Standard;

$os = OperatingSystem::createFromGlobals();

// Check if OS supports a specific standard
if ($os->isSupports(Standard::Posix)) {
    // Standard is supported
}
```
