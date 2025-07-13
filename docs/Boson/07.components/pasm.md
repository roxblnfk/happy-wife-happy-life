# Machine Code Executor

<show-structure for="chapter" depth="2"/>

The PASM (PHP Assembly) provides a set of utilities for 
direct execution of low-level machine code in user space.

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
        <code lang="bash">composer require boson-php/pasm</code>
    </p>
</tldr>

**Requirements:**

* `PHP ^8.4`
* `ext-ffi`

## Usage

To create an assembly executor, you must create the `Boson\Component\Pasm\Executor` 
object. 

The instance contains method `compile($signature, $code)` with 2 arguments: 
- `string $signature` - the signature of the compiled function. 
- `string $code` - the code (body) executed by that compiled function.

This method provides the ability to compile an arbitrary set of machine codes 
into an executed function (`callable` type) that can be executed at any time.

```php
$executor = new \Boson\Component\Pasm\Executor();

$function = $executor->compile(/* signature */, /* code */);
```

<note>
The function is directly associated with the address space in which the 
executable memory was allocated, and after deleting the link (see 
<a href="https://www.php.net/manual/en/features.gc.php">PHP GC</a>) to this 
function, the associated memory will also be automatically cleared.
</note>

### AMD64 (x86_64) Example

Below is an example for getting information about the CPU using the 
[1st leaf](https://software.intel.com/content/www/us/en/develop/download/intel-64-and-ia-32-architectures-software-developers-manual-volume-2a-instruction-set-reference-a-l.html) 
and the `cpuid` instruction.

```php
$executor = new \Boson\Component\Pasm\Executor();

//
// An EAX register returned from cpuid is 32-bit, so it is 
// safest to explicitly specify the return type int32_t.
//
// You can read more about the syntax of callbacks in C/C++ 
// in the documentation or, for example, here:
// - https://www.geeksforgeeks.org/cpp/function-pointers-and-callbacks-in-cpp/
//
const SIGNATURE = 'int32_t(*)()';

//
// In this case, it is machine code that can only be executed on 
// AMD64 (x86_64) and compatible (i.e. x86) architectures.
//
// To convert any assembly language to machine code, 
// you can use, for example:
//  - https://godbolt.org/
//
const CODE = "\xB8\x01\x00\x00\x00" // mov eax, 0x1 
           . "\x0F\xA2"             // cpuid        
           . "\xc3"                 // ret 

/**
 * Compiled function
 *
 * @var callable(): int<−2147483648, 2147483647> $function
 */
$function = $executor->compile(SIGNATURE, CODE);

// 
// Execute this function.
//
// After execution, the result of the EAX (CPUID leaf 1) register 
// will be returned, containing the following information 
// (according to the Intel manual):
//
//  - Stepping (bits 3–0)
//  - Model (bits 7–4)
//  - Family (bits 11–8)
//  - Processor type (bits 13–12)
//  - Extended model (bits 19–16)
//  - Extended family (bits 27–20)
//  - Reserved (bits 31-28)
//
$eax = $result();

$stepping   = $eax & 0x0F;
$model      = ($eax >> 4)  & 0x0F;
$family     = ($eax >> 8)  & 0x0F;
$extModel   = ($eax >> 16) & 0x0F;
$extFamily  = ($eax >> 20) & 0xFF;

echo "\nstepping:    " . $stepping;
echo "\nmodel:       " . $model;
echo "\nfamily:      " . $family;

//
// Other information can be obtained as follows 
// (exactly the same as in the Intel manual)
//
echo "\nfull model:  " . dechex($family === 0x06 || $family === 0x0F 
    ? $model + ($extModel << 4) 
    : $model);

echo "\nfull family: " . ($family === 0x0F 
    ? $family + $extFamily 
    : $family);
  
//  
// The result of executing this code may be as follows:
//
// stepping:    5
// model:       5
// family:      6
// full model:  A5
// full family: 6
//
```