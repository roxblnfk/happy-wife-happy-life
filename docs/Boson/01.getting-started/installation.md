# Installation

<show-structure for="chapter" depth="2"/>

Boson brings together a set of components, each handling a key part of the system:

- [**Runtime**](https://github.com/boson-php/runtime) — acts as the bridge 
  between your PHP code and the underlying operating system. This is the main 
  API that you can use both in runtime and during development.

- [**Compiler**](https://github.com/boson-php/compiler) – allows you to build 
  the results of your work into a finished project ready for distribution.

<note>
Technically there are more repositories and components, but the main ones 
are presented here.
</note>

Together, they create a seamless environment for building rich desktop 
applications with web technologies — without the bloat.


## Runtime

Boson runtime provides the core of the library and allows you to 
run your wonderful applications.

Library is available as [Composer](https://getcomposer.org/doc/) repository and
can be installed using the following command in a root of your project:

```shell
composer require boson-php/runtime
```

<warning>
Since the current release version of the package is <code>0.x</code>, you 
should make sure that your <code>composer.json</code> file contains the 
<code>"minimum-stability": "dev"</code> line, otherwise you will get an 
error message like:
<code-block>
Could not find a version of package boson-php/runtime matching your 
minimum-stability (stable). Require it with an explicit version con
straint allowing its desired stability.
</code-block>
</warning>

Don't forget to include the autoload file in your application.

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$app = new Boson\Application();
```


## Compiler

Boson compiler gives you the ability to assemble the result of your work into 
a final product. That is, into an executable file for the target platform.

Library is available as [Composer](https://getcomposer.org/doc/) repository and 
can be installed using the following command in a root of your project:

```shell
composer require boson-php/compiler --dev
```

<note>
The compiler is only required for development, it is not required for code 
execution, so it is recommended to include it as a <code>--dev</code> package.
</note>

<warning>
Make sure the <code>"minimum-stability"</code> is set correctly.
</warning>

## Requirements

<tabs>
    <tab title="Development">
        List of dependencies required for development.
        <tip>
        The best development experience for Boson is to 
        have PHP running on your development machine directly.
        </tip>
        <tabs>
            <tab title="Windows">
                <list>
                    <li>Windows 10+</li>
                    <li>x86 or Amd64</li>
                    <li>PHP 8.4+
                        <list>
                            <li>An <code>ext-ffi</code> extension</li>
                        </list>
                    </li>
                </list>
            </tab>
            <tab title="MacOS">
                <list>
                    <li>MacOS 12+</li>
                    <li>x86, Amd64 or Arm64</li>
                    <li>PHP 8.4+
                        <list>
                            <li>An <code>ext-ffi</code> extension</li>
                        </list>
                    </li>
                </list>
            </tab>
            <tab title="Linux">
                <list>
                    <li>Linux</li>
                    <li>x86, Amd64 or Arm64</li>
                    <li>PHP 8.4+
                        <list>
                            <li>An <code>ext-ffi</code> extension</li>
                        </list>
                    </li>
                    <li>Frontend dependencies (one of):
                        <tabs>
                        <tab title="GTK 4">
                            <list>
                                <li><code>gtk4</code></li>
                                <li><code>webkitgtk-6.0</code></li>
                            </list>
                        </tab>
                        <tab title="QT 5">
                            <list>
                                <li><code>qt5-qtwebengine</code></li>
                                <li><code>qt5-qtwebchannel</code></li>
                                <li><code>qt5-qtbase-gui</code></li>
                                <li><code>qt5-qtbase</code></li>
                            </list>
                        </tab>
                        <tab title="QT6">
                            <list>
                                <li><code>qt6-qtwebengine</code></li>
                                <li><code>qt6-qtwebchannel</code></li>
                                <li><code>qt6-qtbase-gui</code></li>
                                <li><code>qt6-qtbase</code></li>
                            </list>
                        </tab>
                        </tabs>
                    </li>
                </list>
            </tab>
        </tabs>
    </tab>
    <tab title="Production">
        List of dependencies required for build target.
        <tip>
        PHP is not required on the target platform, as it is 
        supplied with the ready-to-distribute application.
        </tip>
        <warning>
        The project is in development, soThe project is in development, so 
        list of dependencies may change in the future.
        </warning>
        <tabs>
            <tab title="Windows">
                <list>
                    <li>Windows 10+</li>
                    <li>x86 or Amd64</li>
                </list>
            </tab>
            <tab title="MacOS">
                <list>
                    <li>MacOS 12+</li>
                    <li>x86, Amd64 or Arm64</li>
                </list>
            </tab>
            <tab title="Linux">
                <list>
                    <li>Linux</li>
                    <li>x86, Amd64 or Arm64</li>
                    <li>Frontend dependencies (one of):
                        <tabs>
                        <tab title="GTK 4">
                            <list>
                                <li><code>gtk4</code></li>
                                <li><code>webkitgtk-6.0</code></li>
                            </list>
                        </tab>
                        <tab title="QT 5">
                            <list>
                                <li><code>qt5-qtwebengine</code></li>
                                <li><code>qt5-qtwebchannel</code></li>
                                <li><code>qt5-qtbase-gui</code></li>
                                <li><code>qt5-qtbase</code></li>
                            </list>
                        </tab>
                        <tab title="QT6">
                            <list>
                                <li><code>qt6-qtwebengine</code></li>
                                <li><code>qt6-qtwebchannel</code></li>
                                <li><code>qt6-qtbase-gui</code></li>
                                <li><code>qt6-qtbase</code></li>
                            </list>
                        </tab>
                        </tabs>
                    </li>
                </list>
            </tab>
        </tabs>
    </tab>
</tabs>

<warning>
Please note that the application build is not currently available, 
so production dependencies may differ in the future.
</warning>


## Troubleshooting

### An [ext-ffi] disabled in your php.ini

```shell
FFI\Env\Exception\EnvironmentException: An [ext-ffi] disabled in your php.ini
```

<note>
Make sure that the <code>ffi.enable</code> config in your <code>php.ini</code> 
file is set to <code>preload</code> (by default) or <code>true</code>.

<compare>
<code-block lang="ini">
ffi.enable=false
</code-block>
<code-block lang="ini">
ffi.enable=preload
</code-block>
</compare>
</note>

### An [ext-ffi] not available

```shell
FFI\Env\Exception\EnvironmentException: An [ext-ffi] not available
```

<note>
Make sure that FFI extension is installed.

<tabs>
<tab title="Windows">
  <list>
    <li>
      Open your <code>php.ini</code> file.
      <tip>The location of the configuration file can be found using the <code>php --ini</code> console command</tip>
    </li>
    <li>
      Find <code>extension=ffi</code> line and uncomment extension.
      <compare>
      <code-block lang="ini">
      ;extension=ffi
      </code-block>
      <code-block lang="ini">
      extension=ffi
      </code-block>
      </compare>
    </li>
  </list>
</tab>
<tab title="Linux (Debian)">
<code-block lang="bash">
sudo apt update
sudo apt install php8.4-ffi
</code-block>
</tab>
</tabs>
</note>


### Failed loading '.../libboson-linux-xxx.so'

<note>
Make sure that GTK4 is installed.
<tabs>
<tab title="Linux (Debian)">
<code-block lang="bash">
apt install libgtk-4-1 libwebkitgtk-6.0-4
</code-block>
</tab>
<tab title="Linux (Fedora)">
<code-block lang="bash">
dnf install gtk4 webkitgtk6.0
</code-block>
</tab>
<tab title="Linux (FreeBSD)">
<code-block lang="bash">
pkg install webkit2-gtk4
</code-block>
</tab>
</tabs>
</note>

### Illegal instruction (core dumped)

Linux prebuild was compiled with some assumptions, such as the presence of 
<code>sse</code> and <code>avx2</code> instructions.

<note>
Make sure your CPU supports <code>AVX2</code> (Intel Q2 2013, AMD Q2 2015) 
instructions set (<code>cat /proc/cpuinfo</code>).

<b>Note:</b> VirtualBox may not support AVX/AVX2 when running under Hyper-V.
</note>

### libEGL warning: egl: failed to create dri2 screen

<note>
sudo apt-get install libnvidia-egl-wayland1
</note>

### Failed to fully launch dbus-proxy: Child process exited with code 1

<note>
This is <a href="https://bugs.launchpad.net/apparmor/+bug/2046844">apparmor bug</a> and
Ubuntu 24.04 is <a href="https://bugs.launchpad.net/ubuntu/+source/apparmor/+bug/2060810">also affected</a>.

1) It can be temporarily fixed by running code as <code>sudo</code>.
2) Or using following commands:
<code-block lang="bash">
sudo sysctl -w kernel.apparmor_restrict_unprivileged_unconfined=0
sudo sysctl -w kernel.apparmor_restrict_unprivileged_userns=0
</code-block>
</note>
