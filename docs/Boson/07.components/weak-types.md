# Weak Types

<show-structure for="chapter" depth="2"/>

The Weak Types component provides a set of classes for working with weak 
references in PHP. It allows you to store objects without preventing them 
from being garbage collected, and react to their destruction.

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
        <code lang="bash">composer require boson-php/weak-types</code>
    </p>
</tldr>

**Requirements:**

* `PHP ^8.4`


## Usage

### ObservableWeakSet

`ObservableWeakSet` allows you to store a set of objects and track their 
destruction. It's useful when you need to maintain a collection of objects 
and react when they are garbage collected.

```php
use Boson\Component\WeakType\ObservableWeakSet;

// Create a new observable weak set
$set = new ObservableWeakSet();

// Watch an object
$set->watch($object, function (object $ref): void {
    echo "Object has been destroyed, cleaning up resources...\n";
});

// Iterate over all entries
foreach ($set as $object) {
    // Process each object
}

// Get the number of entries
$count = count($set);
```

### ObservableWeakMap

`ObservableWeakMap` allows you to store a set of objects with referenced
values and track their destruction. It's useful when you need to maintain a
mapping between objects and react when they are garbage collected.

```php
use Boson\Component\WeakType\ObservableWeakMap;

// Create a new observable weak map
$map = new ObservableWeakMap();

// Watch an object and its associated value
$map->watch($keyObject, $refObject, function (object $ref): void {
    echo "Object has been destroyed, cleaning up resources...\n";
});

// Find a value by key
$value = $map->find($keyObject);

// Iterate over all entries
foreach ($map as $keyObject => $refObject) {
    // Process each entry
}

// Get the number of entries
$count = count($map);
```