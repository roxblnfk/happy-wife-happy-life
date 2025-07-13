# Building

<show-structure for="chapter" depth="2"/>

To build an executable application file, you should use the `compile` command.

```shell
php vendor/bin/boson compile
```

After running the command you will get something like the following result:

<video src="compilation.mp4" preview-src="compilation.png" />

This command will compile your application into single executable file.
This is enough to distribute the application. NO dependencies (`php`, `node`,
`electron`, etc.) are required anymore. Everything you need will already be
included inside and ready to work!

## Cross-compilation

The following platforms and architectures are available 
for building the application:

- `Windows`
  - `amd64` (`x86_64`)
- `Linux`
  - `amd64` (`x86_64`)
  - `aarch64` (`arm64`)
- `macOS`
  - `amd64` (`x86_64`)
  - `aarch64` (`arm64`)

<note>
The same applies to <code>boson-php/runtime</code>. At the moment, only 
these platforms are supported, even if you distribute the application 
as source code, without building.
</note>