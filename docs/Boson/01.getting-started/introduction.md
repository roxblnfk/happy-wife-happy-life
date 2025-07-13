# Getting Started

<show-structure for="chapter" depth="2"/>

We suggest starting with the tutorial, which walks you step by step through building a Boson application
and preparing it for distribution. You can also explore the examples and API documentation — they're
great resources for learning more and finding new ideas.

## What is Boson?

Boson is an innovative cross-platform desktop application development platform that unleashes the power of web
technologies (PHP, JavaScript, HTML, CSS) with the benefits of native assembly. Its key feature is the integration 
of the Chromium-based WebView engine and PHP interpreter directly into the executable file application. 
This solution allows developers to:

- **Use familiar stack technologies** — create interfaces via HTML/CSS and implement logic in PHP (with
  JavaScript elements) without the need to learn platform-specific languages.

- **Deliver a reliable user experience** on Windows, macOS and Linux thanks to stable rendering via the Chromium engine.

- **Reduce development time** of the single code base counter — changes are automatically synchronized between all platforms.

- **Simplify distribution** — self-contained binaries do not require installation of additional runtimes.

The design feature of Boson makes it the right choice for web developers who want to go beyond browser applications. 
The library fully allows for the need to work with native APIs, instead of the usual workflow with automatic 
translation of web components to the desktop interface. Ready-made applications retain all the advantages 
of native programs, including access to the file system and system resources, while remaining 
cross-platform "out of the box".

## What Boson isn’t

- Boson **isn’t a GUI framework.** We’re not here to dictate how your app should look or feel. Use whatever 
  front-end stack fits your workflow best — React, Angular, Vue, Svelte, jQuery, or just classic HTML 
  and CSS. Prefer Bootstrap, Bulma or Tailwind? Go for it. Your UI, your rules.

- Boson **doesn’t spin up HTTP server** (unlike NativePHP) or rely on Node.js. No unstable workarounds, no 
  extra layers, and no unnecessary data transformations. Just direct, streamlined access to the renderer 
  — all within a single, unified process.

- Boson **doesn't rely on heavy dependencies** and isn’t a fork of Electron or NativePHP. It takes 
  advantage of tools already available on your OS, keeping your application lightweight. Instead of 
  consuming hundreds of megabytes like typical Electron or NativePHP apps, Boson keeps its footprint 
  to just a few kilobytes — efficient by design.

- Boson **doesn’t reinvent PHP** either, like JPHP (Devel Studio/Devel Next). It uses the same modern PHP 
  you already know and love — no forks, no surprises.

And forget about complex setup or custom PHP extensions. Getting started is as easy as running a 
single command: `composer require boson-php/runtime` — and you’re off to the races.

## What is in the docs?

All the official documentation is available from the sidebar. These are the different categories and
what you can expect on each one:

- **Basics**: Documentation pages describing the core components.
- **APIs**: Information on interaction with third-party subsystems.
- **Components**: Description of independent components separately, 
  which you can use outside of runtime.
- **Distribute**: Documentation containing information about building 
  (compilation) an application for distribution.
- **Examples**: Quick references to add features to your Boson app.

## Getting Help

Are you getting stuck anywhere? Here are a few links to places to look:

If you need help with developing your app, our community 
[Telegram](https://t.me/boson_php) is a great place to get advice from other 
Boson app developers.

If you suspect you're running into a bug with the package, please check the 
[GitHub](https://github.com/boson-php/boson) issue tracker to see if any 
existing issues match your problem. If not, feel free to fill out our bug 
report template and submit a new issue.